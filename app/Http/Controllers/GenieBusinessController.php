<?php

namespace App\Http\Controllers;

use App\Mail\PaymentStatusMail;
use App\Models\Transaction;
use App\Services\GenieBusinessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class GenieBusinessController extends Controller
{
    public function __construct(private readonly GenieBusinessService $genieBusiness)
    {
    }

    /**
     * Show the dedicated Genie Business payment page.
     */
    public function index(): Response
    {
        return Inertia::render('Frontend/Pages/GenieBusiness/Index', [
            'integrationReady' => $this->genieBusiness->isConfigured(),
        ]);
    }

    public function initiate(Request $request)
    {
        if (!$this->genieBusiness->isConfigured()) {
            return back()->withErrors([
                'payment' => 'Genie Business credentials or endpoint settings are missing.',
            ]);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:99999999.99',
            'currency' => 'required|in:LKR',
            'student_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'nic_passport' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'email' => 'required|email:rfc,dns',
            'phone' => ['required', 'string', 'regex:/^(\+94|0)?[1-9]\d{8}$/'],
        ], [
            'email.email' => 'Please enter a valid email address',
            'phone.regex' => 'Please enter a valid Sri Lankan mobile number',
        ]);

        $orderPrefix = preg_replace('/[^a-zA-Z0-9]/', '', $validated['reference'] ?? 'GENIE');
        $orderPrefix = $orderPrefix !== '' ? $orderPrefix : 'GENIE';
        $clientRef = $orderPrefix . '-' . now()->format('YmdHis') . '-' . uniqid();

        $description = "{$validated['program']} - {$validated['student_name']} ({$validated['student_id']})";
        if (!empty($validated['reference'])) {
            $description .= " Ref: {$validated['reference']}";
        }

        $transaction = Transaction::create([
            'client_ref' => $clientRef,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'status' => 'pending',
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'student_name' => $validated['student_name'],
            'student_id' => $validated['student_id'],
            'program' => $validated['program'],
            'nic_passport' => $validated['nic_passport'],
            'reference' => $validated['reference'] ?? null,
            'description' => $description,
            'initiated_at' => now(),
            'request_data' => $validated,
        ]);

        $callbackUrl = route('genie-business.callback', ['client_ref' => $clientRef]);
        $result = $this->genieBusiness->initiatePayment([
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'order_id' => $clientRef,
            'description' => $description,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'student_name' => $validated['student_name'],
            'student_id' => $validated['student_id'],
            'program' => $validated['program'],
            'nic_passport' => $validated['nic_passport'],
            'reference' => $validated['reference'] ?? null,
            'callback_url' => $callbackUrl,
            'success_url' => config('genie_business.success_url') ?: $callbackUrl,
            'cancel_url' => config('genie_business.cancel_url') ?: $callbackUrl,
        ]);

        if ($result['status'] !== 'success') {
            $transaction->update([
                'status' => 'failed',
                'response_data' => $result,
            ]);

            return back()->withErrors([
                'payment' => $result['message'] ?? 'Genie Business payment initiation failed.',
            ]);
        }

        $transaction->update([
            'reqid' => $result['request_id'] ?? null,
            'transaction_id' => $result['redirect_url'] ? null : ($result['transaction_id'] ?? null),
            'status' => $result['redirect_url'] ? 'processing' : ($result['normalized_status'] ?? 'processing'),
            'payment_state' => $result['provider_status'] ?? null,
            'response_data' => [
                'initiation' => $result['raw'] ?? [],
            ],
        ]);

        if (!empty($result['redirect_url'])) {
            return redirect()->away($result['redirect_url']);
        }

        $this->updateTransactionFromVerification($transaction, $result);
        $transaction->refresh();

        return $this->showResult($transaction);
    }

    public function callback(Request $request): Response
    {
        Log::info('Genie Business callback received', [
            'query' => $request->query(),
        ]);

        $transaction = $this->resolveTransaction($request);

        if (!$transaction) {
            return Inertia::render('Frontend/Pages/Paycenter/Result', [
                'success' => false,
                'message' => 'Transaction not found for the Genie Business callback.',
            ]);
        }

        if ($transaction->status === 'completed' && $transaction->transaction_id) {
            return $this->showResult($transaction);
        }

        if (in_array($transaction->status, ['failed', 'cancelled'], true)) {
            return $this->showResult($transaction);
        }

        $verification = $this->genieBusiness->verifyPayment($transaction, $request->all());
        $this->updateTransactionFromVerification($transaction, $verification);
        $transaction->refresh();

        return $this->showResult($transaction);
    }

    public function checkStatus(Request $request): JsonResponse
    {
        $transaction = $this->resolveTransaction($request);

        if (!$transaction) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Transaction not found',
            ], 404);
        }

        if ($transaction->status === 'processing') {
            $verification = $this->genieBusiness->verifyPayment($transaction, $request->all());
            $this->updateTransactionFromVerification($transaction, $verification);
            $transaction->refresh();
        }

        return response()->json([
            'status' => $transaction->status,
            'payment_state' => $transaction->payment_state,
            'transaction_id' => $transaction->transaction_id,
            'should_poll' => $transaction->status === 'processing',
        ]);
    }

    public function webhook(Request $request): JsonResponse
    {
        $signatureHeader = (string) config('genie_business.webhook_signature_header', 'X-Genie-Signature');
        $signature = (string) $request->header($signatureHeader, '');

        if (!$this->genieBusiness->verifyWebhookSignature($signature, $request->getContent())) {
            return response()->json(['message' => 'Invalid Genie webhook signature.'], 401);
        }

        $transaction = $this->resolveTransaction($request);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        $this->updateTransactionFromVerification(
            $transaction,
            $this->genieBusiness->normalizeWebhookPayload($request->all())
        );

        return response()->json(['received' => true]);
    }

    private function resolveTransaction(Request $request): ?Transaction
    {
        $payload = $request->all();

        $clientRefs = array_filter(array_unique([
            $request->input('client_ref'),
            $request->input('externalId'),
            data_get($payload, 'externalId'),
            data_get($payload, 'data.externalId'),
            data_get($payload, 'metadata.client_ref'),
        ]));

        foreach ($clientRefs as $clientRef) {
            $transaction = Transaction::where('client_ref', $clientRef)->first();
            if ($transaction) {
                return $transaction;
            }
        }

        $requestIds = array_filter(array_unique([
            $request->input('request_id'),
            $request->input('requestId'),
            $request->input('reqid'),
            $request->input('paymentId'),
            data_get($payload, 'requestId'),
            data_get($payload, 'reqid'),
            data_get($payload, 'paymentId'),
            data_get($payload, 'data.requestId'),
            data_get($payload, 'data.reqid'),
            data_get($payload, 'data.paymentId'),
        ]));

        foreach ($requestIds as $requestId) {
            $transaction = Transaction::where('reqid', $requestId)->first();
            if ($transaction) {
                return $transaction;
            }
        }

        $transactionIds = array_filter(array_unique([
            $request->input('transaction_id'),
            $request->input('transactionId'),
            $request->input('id'),
            data_get($payload, 'transactionId'),
            data_get($payload, 'id'),
            data_get($payload, 'data.transactionId'),
        ]));

        foreach ($transactionIds as $transactionId) {
            $transaction = Transaction::where('transaction_id', $transactionId)->first();
            if ($transaction) {
                return $transaction;
            }
        }

        return null;
    }

    private function updateTransactionFromVerification(Transaction $transaction, array $verification): void
    {
        if (($verification['status'] ?? 'error') !== 'success') {
            $transaction->update([
                'response_data' => array_merge($transaction->response_data ?? [], [
                    'verification_error' => $verification,
                ]),
            ]);

            return;
        }

        $normalizedStatus = $verification['normalized_status'] ?? 'processing';
        $paymentState = $verification['provider_status'] ?? strtoupper($normalizedStatus);
        $responseData = array_merge($transaction->response_data ?? [], [
            'verification' => $verification['raw'] ?? [],
            'fail_reason' => $verification['message'] ?? null,
            'last_checked' => now()->toIso8601String(),
        ]);

        if ($normalizedStatus === 'completed') {
            $transaction->update([
                'status' => 'completed',
                'payment_state' => $paymentState,
                'reqid' => $verification['request_id'] ?? $transaction->reqid,
                'transaction_id' => $verification['transaction_id'] ?? $transaction->transaction_id ?? $verification['request_id'] ?? $transaction->client_ref,
                'completed_at' => now(),
                'response_data' => $responseData,
            ]);

            $this->sendStatusMail($transaction->fresh());

            return;
        }

        if ($normalizedStatus === 'failed' || $normalizedStatus === 'cancelled') {
            $transaction->update([
                'status' => $normalizedStatus,
                'payment_state' => $paymentState,
                'reqid' => $verification['request_id'] ?? $transaction->reqid,
                'transaction_id' => $verification['transaction_id'] ?? $transaction->transaction_id,
                'response_data' => $responseData,
            ]);

            $this->sendStatusMail($transaction->fresh());

            return;
        }

        $transaction->update([
            'status' => 'processing',
            'payment_state' => $paymentState,
            'reqid' => $verification['request_id'] ?? $transaction->reqid,
            'transaction_id' => $verification['transaction_id'] ?? $transaction->transaction_id,
            'response_data' => $responseData,
        ]);
    }

    private function sendStatusMail(Transaction $transaction): void
    {
        if (!$transaction->customer_email) {
            return;
        }

        try {
            Mail::to($transaction->customer_email)->send(new PaymentStatusMail($transaction));
        } catch (\Throwable $exception) {
            Log::error('Failed to send Genie payment status email', [
                'transaction_id' => $transaction->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function showResult(Transaction $transaction): Response
    {
        $responseData = $transaction->response_data ?? [];
        $failReason = $responseData['fail_reason']
            ?? data_get($responseData, 'verification.message')
            ?? data_get($responseData, 'verification.responseText');

        if ($transaction->status === 'completed') {
            return Inertia::render('Frontend/Pages/Paycenter/Result', [
                'success' => true,
                'message' => 'Genie Business payment completed successfully!',
                'transaction' => [
                    'client_ref' => $transaction->client_ref,
                    'transaction_id' => $transaction->transaction_id,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'status' => $transaction->payment_state ?? 'COMPLETED',
                    'card_type' => 'GENIE BUSINESS',
                ],
            ]);
        }

        if ($transaction->status === 'processing') {
            return Inertia::render('Frontend/Pages/Paycenter/Result', [
                'success' => false,
                'message' => 'Genie Business is still processing the payment. Please refresh shortly to check the latest status.',
                'isPending' => true,
                'transaction' => [
                    'client_ref' => $transaction->client_ref,
                    'amount' => $transaction->amount,
                    'currency' => $transaction->currency,
                    'status' => 'PROCESSING',
                    'card_type' => 'GENIE BUSINESS',
                ],
            ]);
        }

        return Inertia::render('Frontend/Pages/Paycenter/Result', [
            'success' => false,
            'message' => 'Genie Business payment failed',
            'transaction' => [
                'client_ref' => $transaction->client_ref,
                'transaction_id' => $transaction->transaction_id,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'status' => $transaction->payment_state ?? strtoupper($transaction->status),
                'card_type' => 'GENIE BUSINESS',
                'fail_reason' => $failReason,
            ],
        ]);
    }
}