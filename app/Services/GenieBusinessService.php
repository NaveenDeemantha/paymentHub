<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenieBusinessService
{
    /**
     * Determine whether the minimum Genie Business credentials have been configured.
     */
    public function isConfigured(): bool
    {
        return (bool) config('genie_business.base_url')
            && (bool) config('genie_business.create_transaction_path')
            && (
                (bool) config('genie_business.api_key')
                || (
                    (bool) config('genie_business.client_id')
                    && (bool) config('genie_business.client_secret')
                    && (bool) config('genie_business.token_url')
                )
            );
    }

    public function initiatePayment(array $data): array
    {
        $payload = $this->buildInitiatePayload($data);
        $url = $this->buildUrl(config('genie_business.create_transaction_path'));

        Log::info('Genie Business create payment request', [
            'url' => $url,
            'payload' => $payload,
        ]);

        try {
            /** @var Response $response */
            $response = $this->sendRequest('POST', $url, $payload);
            $json = $response->json() ?? [];

            Log::info('Genie Business create payment response', [
                'status' => $response->status(),
                'body' => $json,
            ]);

            if (!$response->successful()) {
                return [
                    'status' => 'error',
                    'message' => $this->extractMessage($json) ?? 'Genie Business payment initiation failed.',
                    'http_status' => $response->status(),
                    'raw' => $json,
                ];
            }

            $normalized = $this->normalizeGatewayPayload($json);

            if ($normalized['normalized_status'] === 'failed' && !$normalized['redirect_url']) {
                return [
                    'status' => 'error',
                    'message' => $normalized['message'] ?? 'Genie Business rejected the payment request.',
                    'raw' => $json,
                ];
            }

            return array_merge(['status' => 'success'], $normalized);
        } catch (\Throwable $exception) {
            Log::error('Genie Business initiate payment error', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Failed to connect to Genie Business: ' . $exception->getMessage(),
            ];
        }
    }

    public function verifyPayment(Transaction $transaction, array $callbackPayload = []): array
    {
        $replacements = [
            'id' => $this->resolveLookupId($transaction, $callbackPayload),
            'request_id' => $callbackPayload['request_id'] ?? $callbackPayload['reqid'] ?? $transaction->reqid,
            'transaction_id' => $callbackPayload['transaction_id'] ?? $transaction->transaction_id,
            'client_ref' => $callbackPayload['client_ref'] ?? $transaction->client_ref,
            'external_id' => $callbackPayload['externalId'] ?? $transaction->client_ref,
        ];

        $statusPath = (string) config('genie_business.status_path');
        $url = $this->replacePathPlaceholders($statusPath, $replacements);
        $method = strtoupper((string) config('genie_business.status_method', 'GET'));

        $payload = [
            'id' => $replacements['id'],
            'requestId' => $replacements['request_id'],
            'transactionId' => $replacements['transaction_id'],
            'externalId' => $replacements['external_id'],
        ];

        Log::info('Genie Business verify payment request', [
            'method' => $method,
            'url' => $url,
            'payload' => $payload,
        ]);

        try {
            /** @var Response $response */
            $response = $this->sendRequest($method, $url, $payload, $method === 'GET' ? $payload : []);
            $json = $response->json() ?? [];

            Log::info('Genie Business verify payment response', [
                'status' => $response->status(),
                'body' => $json,
            ]);

            if (!$response->successful()) {
                return [
                    'status' => 'error',
                    'message' => $this->extractMessage($json) ?? 'Unable to verify Genie Business payment status.',
                    'http_status' => $response->status(),
                    'raw' => $json,
                ];
            }

            return array_merge(['status' => 'success'], $this->normalizeGatewayPayload($json));
        } catch (\Throwable $exception) {
            Log::error('Genie Business verify payment error', [
                'message' => $exception->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Failed to verify Genie Business payment: ' . $exception->getMessage(),
            ];
        }
    }

    public function normalizeWebhookPayload(array $payload): array
    {
        return array_merge(['status' => 'success'], $this->normalizeGatewayPayload($payload));
    }

    public function verifyWebhookSignature(string $signature, string $rawBody): bool
    {
        $secret = (string) config('genie_business.webhook_secret');

        if ($secret === '') {
            return true;
        }

        if ($signature === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $secret);

        return hash_equals($expected, $signature);
    }

    private function buildInitiatePayload(array $data): array
    {
        $clientRef = (string) $data['order_id'];
        $amount = $this->normalizeAmount((float) $data['amount'], (string) $data['currency']);

        $payload = [
            'amount' => $amount,
            'currency' => $data['currency'],
            'externalId' => $clientRef,
            'description' => $data['description'] ?? ('Payment for ' . $clientRef),
            'callbackUrl' => $data['callback_url'],
            'successUrl' => $data['success_url'],
            'cancelUrl' => $data['cancel_url'],
            'customer' => [
                'name' => $data['student_name'] ?? null,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'externalId' => $data['student_id'] ?? $clientRef,
            ],
            'metadata' => array_filter([
                'studentId' => $data['student_id'] ?? null,
                'program' => $data['program'] ?? null,
                'nicPassport' => $data['nic_passport'] ?? null,
                'reference' => $data['reference'] ?? null,
            ], static fn ($value) => $value !== null && $value !== ''),
        ];

        if ($merchantId = config('genie_business.merchant_id')) {
            $payload['merchantId'] = $merchantId;
        }

        return $payload;
    }

    private function normalizeAmount(float $amount, string $currency): int|float
    {
        if (!config('genie_business.amount_in_minor_units')) {
            return round($amount, 2);
        }

        $decimalPlaces = match (strtoupper($currency)) {
            'JPY', 'KRW' => 0,
            'KWD', 'BHD', 'OMR' => 3,
            default => 2,
        };

        return (int) round($amount * (10 ** $decimalPlaces));
    }

    private function sendRequest(string $method, string $url, array $payload = [], array $query = [])
    {
        $request = Http::acceptJson()
            ->timeout((int) config('genie_business.timeout_seconds', 30))
            ->withHeaders($this->buildHeaders());

        if (!empty($query)) {
            $request = $request->withQueryParameters(array_filter($query, static fn ($value) => $value !== null && $value !== ''));
        }

        if ($method === 'GET') {
            return $request->get($url);
        }

        return match ($method) {
            'POST' => $request->post($url, $payload),
            'PUT' => $request->put($url, $payload),
            'PATCH' => $request->patch($url, $payload),
            'DELETE' => $request->delete($url, $payload),
            default => $request->post($url, $payload),
        };
    }

    private function buildHeaders(): array
    {
        $headers = ['Content-Type' => 'application/json'];

        $appId = (string) config('genie_business.app_id');
        $appIdHeader = (string) config('genie_business.app_id_header');
        if ($appId !== '' && $appIdHeader !== '') {
            $headers[$appIdHeader] = $appId;
        }

        if ($apiKey = config('genie_business.api_key')) {
            $headerName = (string) config('genie_business.api_key_header', 'Authorization');
            $prefix = trim((string) config('genie_business.api_key_prefix', 'Bearer'));
            $headers[$headerName] = $prefix !== '' ? $prefix . ' ' . $apiKey : $apiKey;

            return $headers;
        }

        if ($token = $this->getAccessToken()) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return $headers;
    }

    private function getAccessToken(): ?string
    {
        $clientId = (string) config('genie_business.client_id');
        $clientSecret = (string) config('genie_business.client_secret');
        $tokenUrl = (string) config('genie_business.token_url');

        if ($clientId === '' || $clientSecret === '' || $tokenUrl === '') {
            return null;
        }

        $cacheKey = 'genie_business.access_token.' . md5($tokenUrl . '|' . $clientId);

        return Cache::remember($cacheKey, now()->addMinutes(50), function () use ($tokenUrl, $clientId, $clientSecret, $cacheKey) {
            $payload = array_filter([
                'grant_type' => config('genie_business.token_grant_type', 'client_credentials'),
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => config('genie_business.token_scope'),
            ], static fn ($value) => $value !== null && $value !== '');

            $request = Http::acceptJson()->timeout((int) config('genie_business.timeout_seconds', 30));

            if (config('genie_business.token_as_form', true)) {
                $request = $request->asForm();
            }

            /** @var Response $response */
            $response = $request->post($tokenUrl, $payload);
            $json = $response->json() ?? [];

            if (!$response->successful()) {
                Log::error('Genie Business token request failed', [
                    'status' => $response->status(),
                    'body' => $json,
                ]);

                Cache::forget($cacheKey);

                return null;
            }

            $token = $this->extractFirst($json, [
                'access_token',
                'token',
                'data.access_token',
                'data.token',
            ]);

            $expiresIn = (int) ($this->extractFirst($json, ['expires_in', 'data.expires_in'], 3600));
            Cache::put($cacheKey, $token, now()->addSeconds(max($expiresIn - 60, 60)));

            return $token;
        });
    }

    private function normalizeGatewayPayload(array $payload): array
    {
        $providerStatus = $this->extractFirst($payload, [
            'status',
            'state',
            'paymentStatus',
            'transactionState',
            'data.status',
            'data.state',
            'data.paymentStatus',
            'data.transactionState',
            'responseData.status',
            'responseData.transactionState',
        ]);

        $message = $this->extractMessage($payload);

        return [
            'normalized_status' => $this->mapStatus($providerStatus, $message),
            'provider_status' => $providerStatus ? Str::upper((string) $providerStatus) : null,
            'redirect_url' => $this->extractFirst($payload, [
                'redirectUrl',
                'checkoutUrl',
                'paymentUrl',
                'paymentPageUrl',
                'hostedPageUrl',
                'data.redirectUrl',
                'data.checkoutUrl',
                'data.paymentUrl',
                'data.paymentPageUrl',
                'responseData.paymentPageUrl',
                'links.checkout.href',
                '_links.checkout.href',
            ]),
            'request_id' => $this->extractFirst($payload, [
                'requestId',
                'reqid',
                'paymentId',
                'id',
                'data.requestId',
                'data.reqid',
                'data.paymentId',
                'data.id',
                'responseData.reqid',
            ]),
            'transaction_id' => $this->extractFirst($payload, [
                'transactionId',
                'txnReference',
                'reference',
                'data.transactionId',
                'data.txnReference',
                'responseData.transactionId',
                'responseData.txnReference',
            ]),
            'message' => $message,
            'raw' => $payload,
        ];
    }

    private function mapStatus(mixed $providerStatus, ?string $message): string
    {
        $value = Str::upper(trim((string) $providerStatus));
        $messageValue = Str::upper(trim((string) $message));

        if (in_array($value, ['COMPLETED', 'SUCCESS', 'PAID', 'AUTHORIZED', 'APPROVED', 'CAPTURED'], true)) {
            return 'completed';
        }

        if (in_array($value, ['FAILED', 'DECLINED', 'ERROR'], true)) {
            return 'failed';
        }

        if (in_array($value, ['CANCELLED', 'CANCELED', 'VOIDED'], true)) {
            return 'cancelled';
        }

        if (in_array($value, ['PENDING', 'PROCESSING', 'CREATED', 'INITIATED'], true)) {
            return 'processing';
        }

        if (Str::contains($messageValue, ['FAIL', 'DECLIN', 'ERROR', 'INVALID', 'REJECT'])) {
            return 'failed';
        }

        if (Str::contains($messageValue, ['CANCEL'])) {
            return 'cancelled';
        }

        return 'processing';
    }

    private function extractMessage(array $payload): ?string
    {
        return $this->extractFirst($payload, [
            'message',
            'reason',
            'error.message',
            'error.description',
            'responseText',
            'responseData.responseText',
            'responseData.reason',
            'data.message',
            'data.reason',
        ]);
    }

    private function extractFirst(array $payload, array $paths, mixed $default = null): mixed
    {
        foreach ($paths as $path) {
            $value = data_get($payload, $path);

            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return $default;
    }

    private function buildUrl(string $path): string
    {
        return rtrim((string) config('genie_business.base_url'), '/') . '/' . ltrim($path, '/');
    }

    private function replacePathPlaceholders(string $path, array $replacements): string
    {
        foreach ($replacements as $key => $value) {
            $path = str_replace('{' . $key . '}', rawurlencode((string) $value), $path);
        }

        return $this->buildUrl($path);
    }

    private function resolveLookupId(Transaction $transaction, array $payload): string
    {
        return (string) (
            $payload['id']
            ?? $payload['paymentId']
            ?? $payload['requestId']
            ?? $payload['reqid']
            ?? $payload['transactionId']
            ?? $payload['externalId']
            ?? $transaction->transaction_id
            ?? $transaction->reqid
            ?? $transaction->client_ref
        );
    }
}
