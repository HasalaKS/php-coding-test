<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

Route::middleware(ThrottleRequests::class . ':api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::post('/create-ticket', [TicketController::class, 'createTicket']);

    // Test route with authentication
    Route::post('/test', [AuthController::class, 'testFunction'])->middleware('auth:sanctum');
});

RateLimiter::for('api', function (Request $request) {
    return $request->user()
        ? Limit::perMinute(60)->by($request->user()->id)
        : Limit::perMinute(30)->by($request->ip());
});

