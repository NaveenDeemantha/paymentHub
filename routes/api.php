<?php

use App\Http\Controllers\Api\PaymentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Payment API Routes (no auth middleware - uses API key)
Route::prefix('payment')->group(function () {
    Route::post('/initiate', [PaymentApiController::class, 'initiate']);
    Route::get('/status/{clientRef}', [PaymentApiController::class, 'status']);
});
