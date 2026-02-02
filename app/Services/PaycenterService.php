<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
     * Initiate a payment request.
     *
     * @param array $data Transaction details
     * @return array
     */
    public function initiatePayment(array $data): array
    {
        $payload = [
            'version' => '1.5',
            'operation' => 'PAYMENT_INIT',
            'requestDate' => now()->format('Y-m-d\TH:i:s.vO'),
            'requestData' => [
                'clientId' => (int) $this->clientId,
                'transactionType' => 'PURCHASE',
                'transactionAmount' => [
                    'paymentAmount' => (int) $data['amount'],
                    'currency' => $data['currency'] ?? 'LKR',
                    // 'totalAmount' => 0,
                    // 'serviceFeeAmount' => 0,
                ],
                'redirect' => [
                    'returnUrl' => route('pay.callback'),
                    'returnMethod' => 'GET',
                ],
                'clientRef' => $data['order_id'] ?? uniqid('order_'),
                'comment' => 'Payment for Order ' . ($data['order_id'] ?? ''),
            ],
            // 'validateOnly' => false,
        ];

        // Add HMAC or other security params if needed
        // $payload['requestData']['clientIdHash'] = ''; 

        $response = Http::withHeaders([
            'AUTHTOKEN' => $this->authToken,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint . '/paycorp-webservice/InterfaceServlet', $payload); // URL suffix from sample

        if ($response->successful()) {
            $json = $response->json();
            // Check if responseData exists and has paymentPageUrl
            if (isset($json['responseData']['paymentPageUrl'])) {
                return [
                    'status' => 'success',
                    'url' => $json['responseData']['paymentPageUrl'],
                    'reqid' => $json['responseData']['reqid'] ?? null,
                ];
            }
        }

        return [
            'status' => 'error',
            'message' => $response->body(),
        ];
    }

    /**
     * Verify a payment callback or status.
     * 
     * @param string $reqid
     * @return array
     */
    public function verifyPayment(string $reqid): array
    {
        $payload = [
            'version' => '1.5',
            'operation' => 'PAYMENT_COMPLETE',
            'requestDate' => now()->format('Y-m-d\TH:i:s.vO'),
            'requestData' => [
                'clientId' => (int) $this->clientId,
                'reqid' => $reqid,
            ],
        ];

        $response = Http::withHeaders([
            'AUTHTOKEN' => $this->authToken,
            'Content-Type' => 'application/json',
        ])->post($this->endpoint . '/paycorp-webservice/InterfaceServlet', $payload);

        return $response->json();
    }
}
