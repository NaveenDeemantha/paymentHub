<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaycenterService
{
    protected string $clientId;
    protected string $authToken;
    protected string $endpoint;
    protected string $hmacSecret;

    public function __construct()
    {
        $this->clientId = config('paycenter.client_id');
        $this->authToken = config('paycenter.auth_token');
        $this->endpoint = config('paycenter.endpoint_url');
        $this->hmacSecret = config('paycenter.hmac_secret');
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

        $payload = [
            'version' => '1.5',
            'operation' => 'PAYMENT_INIT',
            'requestDate' => $requestDate,
            'requestData' => [
                'clientId' => (int) $this->clientId,
                'transactionType' => 'PURCHASE',
                'transactionAmount' => [
                    'paymentAmount' => (float) $data['amount'],
                    'currency' => $data['currency'] ?? 'LKR',
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
                (string) $this->clientId,
                $requestDate
            );
        }

        Log::info('Paycenter PAYMENT_INIT Request', ['payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'AUTHTOKEN' => $this->authToken,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/paycorp-webservice/InterfaceServlet', $payload);

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
     * @return array
     */
    public function verifyPayment(string $reqid): array
    {
        $requestDate = now()->format('Y-m-d\TH:i:s.vO');

        $payload = [
            'version' => '1.5',
            'operation' => 'PAYMENT_COMPLETE',
            'requestDate' => $requestDate,
            'requestData' => [
                'clientId' => (int) $this->clientId,
                'reqid' => $reqid,
            ],
        ];

        // Add HMAC signature
        if ($this->hmacSecret) {
            $payload['requestData']['clientIdHash'] = $this->generateHmac(
                (string) $this->clientId,
                $requestDate
            );
        }

        Log::info('Paycenter PAYMENT_COMPLETE Request', ['reqid' => $reqid]);

        try {
            $response = Http::withHeaders([
                'AUTHTOKEN' => $this->authToken,
                'Content-Type' => 'application/json',
            ])->post($this->endpoint . '/paycorp-webservice/InterfaceServlet', $payload);

            Log::info('Paycenter PAYMENT_COMPLETE Response', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $json = $response->json();

                return [
                    'status' => 'success',
                    'data' => $json,
                    'payment_status' => $json['responseData']['transactionState'] ?? 'UNKNOWN',
                    'transaction_id' => $json['responseData']['transactionId'] ?? null,
                    'client_ref' => $json['responseData']['clientRef'] ?? null,
                    'amount' => $json['responseData']['transactionAmount']['paymentAmount'] ?? null,
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
