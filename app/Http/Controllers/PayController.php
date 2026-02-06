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
            'description' => $gatewayDescription,
            'initiated_at' => now(),
            'request_data' => $validated,
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

}
