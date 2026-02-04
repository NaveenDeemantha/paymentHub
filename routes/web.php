<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayController;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
            'total_transactions' => Transaction::count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'total_amount' => number_format(Transaction::where('status', 'completed')->sum('amount'), 2),
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
    Route::post('/clients/{client}/regenerate-api-key', [\App\Http\Controllers\ClientController::class, 'regenerateApiKey'])->name('clients.regenerate-api-key');
    Route::post('/clients/{client}/regenerate-webhook-secret', [\App\Http\Controllers\ClientController::class, 'regenerateWebhookSecret'])->name('clients.regenerate-webhook-secret');
});
