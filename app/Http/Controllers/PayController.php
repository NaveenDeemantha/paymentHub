<?php

namespace App\Http\Controllers;

use App\Services\PaycenterService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayController extends Controller
{
    protected PaycenterService $paycenter;

    public function __construct(PaycenterService $paycenter)
    {
        $this->paycenter = $paycenter;
    }

    public function index()
    {
        return Inertia::render('Paycenter/Index');
    }

    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'order_id' => 'required|string',
        ]);

        $result = $this->paycenter->initiatePayment($validated);

        if ($result['status'] === 'success') {
            // In Inertia, we generally redirect or render a view.
            // For iframe integration, we'll render a page that contains the iframe.
            return Inertia::render('Paycenter/Payment', [
                'paymentUrl' => $result['url'],
            ]);
        }

        return back()->with('error', 'Payment initialization failed: ' . ($result['message'] ?? 'Unknown error'));
    }

    public function callback(Request $request)
    {
        $reqid = $request->input('reqid');

        if (!$reqid) {
            return to_route('pay.index')->with('error', 'Invalid callback data');
        }

        $result = $this->paycenter->verifyPayment($reqid);

        return Inertia::render('Paycenter/Result', [
            'result' => $result,
        ]);
    }
}
