<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayController;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Temporary test route for email - VERIFIED
Route::get('/test-payment-email', function () {
    try {
        $transaction = new \App\Models\Transaction([
            'client_ref' => 'TEST-' . time(),
            'amount' => 5000.00,
            'currency' => 'LKR',
            'status' => 'completed',
            'description' => 'Test Payment Transaction',
        ]);

        // Manually set attributes that aren't fillable or need improved mocking
        $transaction->updated_at = now();
        $transaction->created_at = now();

        $email = 'naveenpunchihewa9@gmail.com';

        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\PaymentStatusMail($transaction));

        return "Email sent successfully to $email";
    } catch (\Exception $e) {
        return "Error sending email: " . $e->getMessage();
    }
});

// Public Landing Page with Bank Selection
Route::get('/', function () {
    return Inertia::render('Frontend/Pages/Home/index');
})->name('home');

// Public Payment Gateway Routes (No authentication required)
Route::get('/pay', [PayController::class, 'index'])->name('pay.index');
Route::post('/pay/initiate', [PayController::class, 'initiate'])->name('pay.initiate');
Route::get('/pay/callback', [PayController::class, 'callback'])->name('pay.callback');

// Admin Authentication Routes
require __DIR__ . '/auth.php';

// Admin Dashboard Routes (Authentication required)
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $stats = [
            'total_clients' => Client::count(),
            'total_transactions' => Transaction::whereDate('created_at', today())->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'processing_transactions' => Transaction::where('status', 'processing')->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
            'total_amount_lkr' => number_format(Transaction::where('status', 'completed')->where('currency', 'LKR')->sum('amount')
                ?? 0, 2),
            'total_amount_usd' => number_format(Transaction::where('status', 'completed')->where('currency', 'USD')->sum('amount')
                ?? 0, 2),
            'today_total_amount_lkr' => number_format(Transaction::whereDate('created_at', today())->where(
                'status',
                'completed'
            )->where('currency', 'LKR')->sum('amount') ?? 0, 2),
            'today_total_amount_usd' => number_format(Transaction::whereDate('created_at', today())->where(
                'status',
                'completed'
            )->where('currency', 'USD')->sum('amount') ?? 0, 2),
        ];

        $recentTransactions = Transaction::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Admin/Pages/Dashboard', [
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
        ]);
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Client Management
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::post('/clients/{client}/regenerate-api-key', [
        \App\Http\Controllers\ClientController::class,
        'regenerateApiKey'
    ])->name('clients.regenerate-api-key');
    Route::post('/clients/{client}/regenerate-webhook-secret', [
        \App\Http\Controllers\ClientController::class,
        'regenerateWebhookSecret'
    ])->name('clients.regenerate-webhook-secret');

    // Transaction Management
    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [
        \App\Http\Controllers\TransactionController::class,
        'show'
    ])->name('transactions.show');
});

// Temporary test route for email
Route::get('/test-mail', function () {
    $transaction = new \App\Models\Transaction([
        'client_ref' => 'TEST-' . time(),
        'amount' => 1250.00,
        'currency' => 'LKR',
        'status' => 'completed',
        'customer_email' => 'navipunchihewa@gmail.com', // Replace with a valid email for testing if needed
        'description' => 'Test Payment Transaction',
        'updated_at' => now(),
    ]);

    // Use the actual mailable class
    return new \App\Mail\PaymentStatusMail($transaction);
});

// Test payment success callback simulation
Route::get('/test-success-callback', function () {
    // Create a test transaction
    $transaction = \App\Models\Transaction::create([
        'client_ref' => 'TEST-SUCCESS-' . time(),
        'reqid' => 'TEST-REQID-' . uniqid(),
        'amount' => 2500.00,
        'currency' => 'LKR',
        'status' => 'processing',
        'customer_email' => 'test@example.com',
        'customer_phone' => '+94777123456',
        'student_name' => 'John Doe',
        'student_id' => 'ST12345',
        'program' => 'Computer Science',
        'nic_passport' => '123456789V',
        'description' => 'Test successful payment',
        'initiated_at' => now(),
    ]);

    // Simulate successful callback
    return redirect()->route('pay.callback', ['reqid' => $transaction->reqid]);
});

// Test payment failure callback simulation
Route::get('/test-failure-callback', function () {
    // Create a test transaction
    $transaction = \App\Models\Transaction::create([
        'client_ref' => 'TEST-FAILURE-' . time(),
        'reqid' => 'TEST-REQID-FAIL-' . uniqid(),
        'amount' => 1500.00,
        'currency' => 'USD',
        'status' => 'processing',
        'customer_email' => 'test-fail@example.com',
        'customer_phone' => '+94777654321',
        'student_name' => 'Jane Smith',
        'student_id' => 'ST67890',
        'program' => 'Business Administration',
        'nic_passport' => '987654321V',
        'description' => 'Test failed payment',
        'initiated_at' => now(),
    ]);

    // Simulate failed callback
    return redirect()->route('pay.callback', ['reqid' => $transaction->reqid]);
});
