<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayController;
use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home/index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

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

    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'recentTransactions' => $recentTransactions,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Paycenter Routes - Public access
Route::get('/pay', [PayController::class, 'index'])->name('pay.index');
Route::post('/pay/initiate', [PayController::class, 'initiate'])->name('pay.initiate');

// Authenticated routes
Route::middleware('auth')->group(function () {
    
    // Client Management
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::post('/clients/{client}/regenerate-api-key', [\App\Http\Controllers\ClientController::class, 'regenerateApiKey'])->name('clients.regenerate-api-key');
    Route::post('/clients/{client}/regenerate-webhook-secret', [\App\Http\Controllers\ClientController::class, 'regenerateWebhookSecret'])->name('clients.regenerate-webhook-secret');
});

// Public callback route (no auth required)
Route::get('/pay/callback', [PayController::class, 'callback'])->name('pay.callback');
