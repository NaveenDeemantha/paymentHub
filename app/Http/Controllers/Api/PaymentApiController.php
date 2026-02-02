<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Transaction;
use App\Services\PaycenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends Controller
{
    protected PaycenterService $paycenter;

    public function __construct(PaycenterService $paycenter)
    {
        $this->paycenter = $paycenter;
    }

    /**
     * Initiate payment from external website
     * POST /api/payment/initiate
     */
    public function initiate(Request $request)
    {
        // Get client from API key
        $apiKey = $request->header('X-API-Key') ?? $request->input('api_key');
        $client = Client::where('api_key', $apiKey)->where('is_active', true)->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive client API key'
            ], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string|size:3',
            'order_id' => 'required|string|max:255',
            'description' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'return_url' => 'nullable|url', // Optional: override client's default return URL
            'metadata' => 'nullable|array', // Custom data to store
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Generate unique client reference
        $clientRef = $client->id . '-' . $validated['order_id'] . '-' . time();

        // Create transaction record
        $transaction = Transaction::create([
            'client_id' => $client->id,
            'client_ref' => $clientRef,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'LKR',
            'status' => 'pending',
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_phone' => $validated['customer_phone'] ?? null,
            'description' => $validated['description'] ?? 'Payment',
            'initiated_at' => now(),
            'request_data' => array_merge($validated, [
                'client_order_id' => $validated['order_id'],
                'metadata' => $validated['metadata'] ?? [],
            ]),
        ]);

        // Prepare payment data
        $paymentData = [
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'LKR',
            'order_id' => $clientRef,
            'description' => $validated['description'] ?? 'Payment',
            'email' => $validated['customer_email'] ?? null,
            'phone' => $validated['customer_phone'] ?? null,
        ];

        // Call Paycenter API
        $result = $this->paycenter->initiatePayment($paymentData);

        if ($result['status'] === 'success') {
            // Update transaction with reqid
            $transaction->update([
                'reqid' => $result['reqid'],
                'status' => 'processing',
            ]);

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'client_ref' => $clientRef,
                'payment_url' => $result['redirect_url'],
                'message' => 'Redirect user to payment_url to complete payment'
            ]);
        }

        // Payment initiation failed
        $transaction->update([
            'status' => 'failed',
            'response_data' => $result,
        ]);

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Payment initialization failed'
        ], 400);
    }

    /**
     * Check payment status
     * GET /api/payment/status/{clientRef}
     */
    public function status(Request $request, string $clientRef)
    {
        // Get client from API key
        $apiKey = $request->header('X-API-Key') ?? $request->input('api_key');
        $client = Client::where('api_key', $apiKey)->where('is_active', true)->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or inactive client API key'
            ], 401);
        }

        // Find transaction
        $transaction = Transaction::where('client_id', $client->id)
            ->where('client_ref', $clientRef)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'transaction' => [
                'id' => $transaction->id,
                'client_ref' => $transaction->client_ref,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'status' => $transaction->status,
                'payment_state' => $transaction->payment_state,
                'transaction_id' => $transaction->transaction_id,
                'created_at' => $transaction->created_at,
                'completed_at' => $transaction->completed_at,
                'metadata' => $transaction->request_data['metadata'] ?? null,
            ]
        ]);
    }
}
