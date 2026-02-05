<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PaycenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PayController extends Controller
{
    protected PaycenterService $paycenter;

    public function __construct(PaycenterService $paycenter)
    {
        $this->paycenter = $paycenter;
    }

    /**
     * Show payment initiation form
     */
    public function index()
    {
        return Inertia::render('Frontend/Pages/Paycenter/Index');
    }

    /**
     * Initiate payment and redirect to Paycenter hosted page
     */
    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:99999999.99',
            'currency' => 'required|in:LKR,USD',
            'student_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:255',
            'program' => 'required|string|max:255',
            'nic_passport' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        // Generate unique client reference
        // Use reference as prefix if available, otherwise ORD
        $orderPrefix = $validated['reference'] ?? 'ORD';
        // Clean prefix to ensure it's alphanumeric for safety
        $orderPrefix = preg_replace('/[^a-zA-Z0-9]/', '', $orderPrefix);
        if (empty($orderPrefix))
            $orderPrefix = 'ORD';

        $clientRef = $orderPrefix . '-' . now()->format('YmdHis') . '-' . uniqid();

        // Format description for the gateway
        $gatewayDescription = "{$validated['program']} - {$validated['student_name']} ({$validated['student_id']})";
        if (!empty($validated['reference'])) {
            $gatewayDescription .= " Ref: {$validated['reference']}";
        }

        // Create transaction record
        // Support both authenticated and non-authenticated users
        $transaction = Transaction::create([
            'client_ref' => $clientRef,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'status' => 'pending',
            'user_id' => Auth::id(), // Can be null for non-authenticated users
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
            'description' => $gatewayDescription, // Start with gateway description
            'initiated_at' => now(),
            'request_data' => $validated, // Stores all student info
        ]);

        // Prepare payment data
        $paymentData = [
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'order_id' => $clientRef,
            'description' => $gatewayDescription,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ];

        // Call Paycenter API
        $result = $this->paycenter->initiatePayment($paymentData);

        if ($result['status'] === 'success') {
            // Update transaction with reqid
            $transaction->update([
                'reqid' => $result['reqid'],
                'status' => 'processing',
            ]);

            // IMPORTANT: For Hosted Redirect method, we redirect user to Paycenter's page
            // They will pay there and Paycenter will redirect back to our callback URL
            return redirect()->away($result['redirect_url']);
        }

        // Payment initiation failed
        $transaction->update([
            'status' => 'failed',
            'response_data' => $result,
        ]);

        return back()->withErrors([
            'payment' => $result['message'] ?? 'Payment initialization failed. Please try again.'
        ]);
    }

    /**
     * Handle callback from Paycenter after payment
     * This is where Paycenter redirects the user after payment attempt
     */
    public function callback(Request $request)
    {
        // Get reqid from query parameters
        $reqid = $request->input('reqid');

        Log::info('Paycenter callback received', [
            'reqid' => $reqid,
            'all_params' => $request->all()
        ]);

        if (!$reqid) {
            return Inertia::render('Frontend/Pages/Paycenter/Result', [
                'success' => false,
                'message' => 'Invalid callback data received from payment gateway',
            ]);
        }

        // Find transaction by reqid
        $transaction = Transaction::where('reqid', $reqid)->first();

        if (!$transaction) {
            Log::error('Transaction not found for reqid', ['reqid' => $reqid]);

            return Inertia::render('Frontend/Pages/Paycenter/Result', [
                'success' => false,
                'message' => 'Transaction not found',
            ]);
        }

        // Verify payment status with Paycenter
        $verification = $this->paycenter->verifyPayment($reqid, $transaction->currency);

        if ($verification['status'] === 'success') {
            $paymentStatus = $verification['payment_status'] ?? 'UNKNOWN';
            $transactionId = $verification['transaction_id'];

            // Update transaction based on payment status
            if (in_array($paymentStatus, ['COMPLETED', 'SUCCESS', 'AUTHORIZED'])) {
                $transaction->update([
                    'status' => 'completed',
                    'payment_state' => $paymentStatus,
                    'transaction_id' => $transactionId,
                    'completed_at' => now(),
                    'response_data' => $verification['data'],
                ]);

                // Send webhook to client if transaction has client_id
                if ($transaction->client_id) {
                    $this->sendWebhook($transaction);
                }

                // If transaction is from external client (has client_id), redirect to client's return URL
                if ($transaction->client_id && $transaction->client) {
                    $returnUrl = $transaction->request_data['return_url'] ?? $transaction->client->return_url;
                    $redirectUrl = $returnUrl . '?' . http_build_query([
                        'status' => 'success',
                        'client_ref' => $transaction->client_ref,
                        'transaction_id' => $transactionId,
                        'amount' => $transaction->amount,
                        'currency' => $transaction->currency,
                    ]);

                    return redirect()->away($redirectUrl);
                }

                return Inertia::render('Frontend/Pages/Paycenter/Result', [
                    'success' => true,
                    'message' => 'Payment completed successfully!',
                    'transaction' => [
                        'client_ref' => $transaction->client_ref,
                        'transaction_id' => $transactionId,
                        'amount' => $transaction->amount,
                        'currency' => $transaction->currency,
                        'status' => $paymentStatus,
                    ],
                ]);
            } elseif (in_array($paymentStatus, ['FAILED', 'DECLINED', 'CANCELLED'])) {
                $transaction->update([
                    'status' => 'failed',
                    'payment_state' => $paymentStatus,
                    'response_data' => $verification['data'],
                ]);

                // Send webhook to client
                if ($transaction->client_id) {
                    $this->sendWebhook($transaction);
                }

                // Redirect to client's return URL if external transaction
                if ($transaction->client_id && $transaction->client) {
                    $returnUrl = $transaction->request_data['return_url'] ?? $transaction->client->return_url;
                    $redirectUrl = $returnUrl . '?' . http_build_query([
                        'status' => 'failed',
                        'client_ref' => $transaction->client_ref,
                        'payment_state' => $paymentStatus,
                    ]);

                    return redirect()->away($redirectUrl);
                }

                return Inertia::render('Frontend/Pages/Paycenter/Result', [
                    'success' => false,
                    'message' => 'Payment was ' . strtolower($paymentStatus),
                    'transaction' => [
                        'client_ref' => $transaction->client_ref,
                        'status' => $paymentStatus,
                    ],
                ]);
            } else {
                // Payment is still pending or in unknown state
                $transaction->update([
                    'payment_state' => $paymentStatus,
                    'response_data' => $verification['data'],
                ]);

                return Inertia::render('Frontend/Pages/Paycenter/Result', [
                    'success' => false,
                    'message' => 'Payment status: ' . $paymentStatus,
                    'transaction' => [
                        'client_ref' => $transaction->client_ref,
                        'status' => $paymentStatus,
                    ],
                ]);
            }
        }

        // Verification failed
        Log::error('Payment verification failed', [
            'reqid' => $reqid,
            'verification' => $verification
        ]);

        return Inertia::render('Frontend/Pages/Paycenter/Result', [
            'success' => false,
            'message' => 'Failed to verify payment status',
        ]);
    }

    /**
     * Show transaction history for authenticated user
     */
    public function transactions()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Frontend/Pages/Paycenter/Transactions', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * Send webhook notification to client
     */
    protected function sendWebhook(Transaction $transaction): void
    {
        if (!$transaction->client || !$transaction->client->webhook_url) {
            return;
        }

        try {
            $payload = [
                'event' => 'payment.' . $transaction->status,
                'transaction_id' => $transaction->id,
                'client_ref' => $transaction->client_ref,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'status' => $transaction->status,
                'payment_state' => $transaction->payment_state,
                'paycenter_transaction_id' => $transaction->transaction_id,
                'completed_at' => $transaction->completed_at?->toIso8601String(),
                'metadata' => $transaction->request_data['metadata'] ?? null,
            ];

            // Generate signature
            $signature = hash_hmac('sha256', json_encode($payload), $transaction->client->webhook_secret);

            Http::withHeaders([
                'X-Webhook-Signature' => $signature,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($transaction->client->webhook_url, $payload);

            Log::info('Webhook sent', [
                'client_id' => $transaction->client_id,
                'transaction_id' => $transaction->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook failed', [
                'client_id' => $transaction->client_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
