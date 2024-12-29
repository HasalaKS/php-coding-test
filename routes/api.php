<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;

Route::middleware(ThrottleRequests::class . ':api')->group(function () {
    // routes related to the login
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // routes related to the tickets
    Route::post('/create-ticket', [TicketController::class, 'createTicket']);
    Route::get('/get-tickets', [TicketController::class, 'getTickets'])->middleware('auth:sanctum');
    Route::post('/create-reply-to-ticket', [TicketController::class, 'createReplyForTicket'])->middleware('auth:sanctum');
    Route::get('/tickets/{referenceNumber}', [TicketController::class, 'searchTicket']);
});

RateLimiter::for('api', function (Request $request) {
    return $request->user()
        ? Limit::perMinute(60)->by($request->user()->id)
        : Limit::perMinute(30)->by($request->ip());
});

