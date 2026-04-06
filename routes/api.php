<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\PlanPriceController;
use App\Http\Controllers\Api\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
});

// Public auth routes
Route::post('login', [AuthController::class, 'login']);

Route::middleware('api')->group(function () {
    Route::apiResource('plans', PlanController::class);
    Route::apiResource('plan-prices', PlanPriceController::class);

    Route::post('subscriptions/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::apiResource('subscriptions', SubscriptionController::class);

    Route::post('payments', [PaymentController::class, 'store']);
    Route::post('payments/{id}/update', [PaymentController::class, 'update']);
});

// Protected auth routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});
