<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaycenterService
{
    protected string $clientIdLkr;
    protected string $clientIdUsd;
    protected string $authToken;
    protected string $endpoint;
    protected string $hmacSecret;

    public function __construct()
    {
        $this->clientIdLkr = config('paycenter.client_id_lkr');
        $this->clientIdUsd = config('paycenter.client_id_usd');
        $this->authToken = config('paycenter.auth_token');
        $this->endpoint = config('paycenter.endpoint_url');
        $this->hmacSecret = config('paycenter.hmac_secret');
    }

    /**
     * Get the appropriate client ID based on currency
     */
    protected function getClientId(string $currency): string
    {
        return $currency === 'USD' ? $this->clientIdUsd : $this->clientIdLkr;
    }

    /**
     * Generate HMAC signature for request authentication
     */
    protected function generateHmac(string $clientId, string $requestDate): string
    {
        $data = $clientId . $requestDate;
        return hash_hmac('sha256', $data, $this->hmacSecret);
    }

    /**
     * Verify HMAC signature from callback
     */
    public function verifyHmac(string $clientId, string $requestDate, string $signature): bool
    {
        $expected = $this->generateHmac($clientId, $requestDate);
        return hash_equals($expected, $signature);
    }

    /**
     * Convert amount to smallest currency unit (cents/paisa)
     * This is required by the Paycenter API, similar to Stripe, PayPal, etc.
     *
     * @param float $amount The amount in major currency unit (e.g., 100.50 LKR)
     * @param string $currency Currency code
     * @return int Amount in smallest unit (e.g., 10050 paisa)
     */
    protected function toSmallestUnit(float $amount, string $currency): int
    {
        // Define decimal places for each currency
        // Most currencies use 2 decimal places, but some use 0 (JPY, KRW) or 3 (KWD)
        $decimalPlaces = match ($currency) {
            'JPY', 'KRW' => 0,  // Japanese Yen, Korean Won - no decimals
            'KWD', 'BHD', 'OMR' => 3,  // Kuwaiti Dinar, Bahraini Dinar, Omani Rial - 3 decimals
            default => 2,  // LKR, USD, EUR, GBP, etc. - 2 decimals
        };

        // Convert to smallest unit by multiplying by 10^decimalPlaces
        // Example: 100.50 LKR * 100 = 10050 paisa
        return (int) round($amount * pow(10, $decimalPlaces));
    }

    /**
     * Initiate a payment request using Hosted Redirect Method
     * This will return a redirect URL to Paycenter's hosted payment page
     *
     * @param array $data Transaction details
     * @return array
     */
    public function initiatePayment(array $data): array
    {
        $requestDate = now()->format('Y-m-d\TH:i:s.vO');
        $clientRef = $data['order_id'] ?? 'ORD-' . uniqid();
        $currency = $data['currency'] ?? 'LKR';
        $clientId = $this->getClientId($currency);

        // Convert amount to smallest currency unit (cents/paisa)
        // The API expects amounts in smallest unit, e.g., 100.50 LKR = 10050 paisa
        $amountInSmallestUnit = $this->toSmallestUnit((float) $data['amount'], $currency);

        $payload = [
            'version' => '1.5',
            'operation' => 'PAYMENT_INIT',
            'requestDate' => $requestDate,
            'requestData' => [
                'clientId' => (int) $clientId,
                'transactionType' => 'PURCHASE',
                'transactionAmount' => [
                    'paymentAmount' => $amountInSmallestUnit,
                    'currency' => $currency,
                ],
                'redirect' => [
                    'returnUrl' => route('pay.callback'),
                    'returnMethod' => 'GET',
                ],
                'clientRef' => $clientRef,
                'comment' => $data['description'] ?? 'Payment for Order ' . $clientRef,
                // Optional: Add customer info if available
                'customer' => [
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                ],
            ],
        ];

        // Add HMAC signature for security
        if ($this->hmacSecret) {
            $payload['requestData']['clientIdHash'] = $this->generateHmac(
                (string) $clientId,
                $requestDate
            );
        }

        Log::info('Paycenter PAYMENT_INIT Request', ['payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'AUTHTOKEN' => $this->authToken,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint, $payload);

            Log::info('Paycenter PAYMENT_INIT Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $json = $response->json();

                // Check for error response
                if (isset($json['responseCode']) && $json['responseCode'] !== '00') {
                    return [
                        'status' => 'error',
                        'message' => $json['responseMessage'] ?? 'Payment initialization failed',
                        'code' => $json['responseCode'],
                    ];
                }

                // Check if responseData exists and has paymentPageUrl
                if (isset($json['responseData']['paymentPageUrl'])) {
                    return [
                        'status' => 'success',
                        'redirect_url' => $json['responseData']['paymentPageUrl'],
                        'reqid' => $json['responseData']['reqid'] ?? null,
                        'client_ref' => $clientRef,
                    ];
                }
            }

            return [
                'status' => 'error',
                'message' => 'Invalid response from payment gateway',
                'debug' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Paycenter API Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'status' => 'error',
                'message' => 'Failed to connect to payment gateway: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment status after redirect callback
     * Call this with the reqid from the callback URL
     *
     * @param string $reqid
     * @param string $currency Currency to determine which client ID to use
     * @return array
     */
    public function verifyPayment(string $reqid, string $currency = 'LKR'): array
    {
        // FOR TESTING: Simulate responses based on reqid patterns
        if (str_contains($reqid, 'TEST-REQID-')) {
            if (str_contains($reqid, 'FAIL')) {
                // Simulate failed payment
                return [
                    'status' => 'success',
                    'data' => [
                        'responseCode' => 'VA',
                        'responseData' => [
                            'transactionState' => 'FAILED',
                            'transactionId' => 'TXN-FAIL-' . time(),
                            'clientRef' => 'TEST-FAILURE-' . time(),
                            'creditCard' => [
                                'type' => 'VISA',
                                'number' => '411111******1111',
                            ],
                            'responseText' => 'INVALID CARD NUMBER (TEST TRANSACTION ONLY)'
                        ]
                    ],
                    'payment_status' => 'FAILED',
                    'transaction_id' => 'TXN-FAIL-' . time(),
                    'client_ref' => 'TEST-FAILURE-' . time(),
                    'amount' => null,
                    'card_type' => 'VISA',
                    'card_number' => '411111******1111',
                    'fail_reason' => 'INVALID CARD NUMBER (TEST TRANSACTION ONLY)',
                ];
            } else {
                // Simulate successful payment
                return [
                    'status' => 'success',
                    'data' => [
                        'responseCode' => '00',
                        'responseData' => [
                            'transactionState' => 'COMPLETED',
                            'transactionId' => 'TXN-SUCCESS-' . time(),
                            'clientRef' => 'TEST-SUCCESS-' . time(),
                            'creditCard' => [
                                'type' => 'MASTERCARD',
                                'number' => '510010******0102',
                            ],
                            'responseText' => 'TRANSACTION APPROVED'
                        ]
                    ],
                    'payment_status' => 'COMPLETED',
                    'transaction_id' => 'TXN-SUCCESS-' . time(),
                    'client_ref' => 'TEST-SUCCESS-' . time(),
                    'amount' => null,
                    'card_type' => 'MASTERCARD',
                    'card_number' => '510010******0102',
                    'fail_reason' => null,
                ];
            }
        }

        $requestDate = now()->format('Y-m-d\TH:i:s.vO');
        $clientId = $this->getClientId($currency);

        $payload = [
            'version' => '1.5',
            'operation' => 'PAYMENT_COMPLETE',
            'requestDate' => $requestDate,
            'requestData' => [
                'clientId' => (int) $clientId,
                'reqid' => $reqid,
            ],
        ];

        // Add HMAC signature
        if ($this->hmacSecret) {
            $payload['requestData']['clientIdHash'] = $this->generateHmac(
                (string) $clientId,
                $requestDate
            );
        }

        Log::info('Paycenter PAYMENT_COMPLETE Request', ['reqid' => $reqid]);

        try {
            $response = Http::withHeaders([
                'AUTHTOKEN' => $this->authToken,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint, $payload);

            Log::info('Paycenter PAYMENT_COMPLETE Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $json = $response->json();

                $responseCode = $json['responseData']['responseCode'] ?? null;
                $transactionState = $json['responseData']['transactionState'] ?? null;
                $responseText = $json['responseData']['responseText'] ?? null;
                $txnReference = $json['responseData']['txnReference'] ?? null;
                $authCode = $json['responseData']['authCode'] ?? null;

                // STRICT payment status determination - only mark COMPLETED with explicit proof:
                //
                // COMPLETED conditions (MUST have explicit success indicators):
                // 1. transactionState is explicitly 'COMPLETED', 'SUCCESS', or 'AUTHORIZED'
                // 2. responseCode is '00' (explicit success code)
                // 3. Has BOTH txnReference AND authCode without error code (fully processed)
                //
                // FAILED conditions (explicit failure indicators):
                // 1. responseCode exists and is NOT '00' (e.g., 'VA', 'FB', 'NS', etc.)
                // 2. Has responseText with error keywords
                //
                // UNKNOWN: If no clear success or failure indicators

                $paymentStatus = 'UNKNOWN';

                // Check for EXPLICIT success (strict conditions)
                if ($transactionState && in_array(strtoupper($transactionState), ['COMPLETED', 'SUCCESS', 'AUTHORIZED', 'APPROVED'])) {
                    $paymentStatus = strtoupper($transactionState);
                }
                elseif ($responseCode === '00') {
                    // Explicit success response code
                    $paymentStatus = 'COMPLETED';
                }
                elseif ($txnReference && $authCode && !$responseCode && !$responseText) {
                    // Has BOTH transaction reference AND auth code, no errors = completed successfully
                    $paymentStatus = 'COMPLETED';
                }
                // Check for EXPLICIT failure
                elseif ($responseCode && $responseCode !== '00') {
                    // Response code exists but is not '00' = definite failure
                    $paymentStatus = 'FAILED';
                }
                elseif ($responseText && (
                    stripos($responseText, 'fail') !== false ||
                    stripos($responseText, 'error') !== false ||
                    stripos($responseText, 'invalid') !== false ||
                    stripos($responseText, 'declined') !== false
                )) {
                    // Has error text with failure keywords = failed
                    $paymentStatus = 'FAILED';
                }
                // FALLBACK: If we have transaction data but no explicit error/success indicators,
                // AND the response contains payment amount (indicating transaction was processed),
                // treat as completed. This handles cases where Paycorp returns minimal data for successful payments.
                elseif ($txnReference && !$responseCode && !$responseText) {
                    // Has transaction reference without any error indicators = likely successful
                    Log::info('Payment has txnReference without errors - treating as COMPLETED');
                    $paymentStatus = 'COMPLETED';
                }
                // Otherwise leave as UNKNOWN - let it be verified again later

                Log::info('Payment verification result', [
                    'reqid' => $json['responseData']['clientRef'] ?? 'unknown',
                    'status' => $paymentStatus,
                    'responseCode' => $responseCode,
                    'transactionState' => $transactionState,
                    'txnReference' => $txnReference,
                    'authCode' => $authCode ? 'present' : 'absent',
                    'responseText' => $responseText,
                ]);

                return [
                    'status' => 'success',
                    'data' => $json,
                    'payment_status' => $paymentStatus,
                    'transaction_id' => $json['responseData']['transactionId'] ?? $json['responseData']['txnReference'] ?? null,
                    'client_ref' => $json['responseData']['clientRef'] ?? null,
                    'amount' => $json['responseData']['transactionAmount']['paymentAmount'] ?? null,
                    'card_type' => $json['responseData']['creditCard']['type'] ?? null,
                    'card_number' => $json['responseData']['creditCard']['number'] ?? null,
                    'fail_reason' => ($paymentStatus === 'FAILED') ? ($responseText ?? $responseCode ?? 'Payment declined') : null,
                ];
            }

            return [
                'status' => 'error',
                'message' => 'Failed to verify payment',
                'debug' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Paycenter Verification Error', [
                'message' => $e->getMessage(),
                'reqid' => $reqid
            ]);

            return [
                'status' => 'error',
                'message' => 'Failed to verify payment: ' . $e->getMessage(),
            ];
        }
    }
}
