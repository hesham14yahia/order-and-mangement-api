<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;

Route::group(["prefix" => "v1"], function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::group(['prefix' => 'orders'], function () {
            Route::post('confirm/{order}', [OrderController::class,'confirm']);
            Route::post('cancel/{order}', [OrderController::class,'cancel']);
        });
        Route::apiResource('orders', OrderController::class);

        Route::group(['prefix' => 'payments'], function () {
            Route::get('/', [PaymentController::class,'index']);
            Route::get('/{order}/', [PaymentController::class,'show']);
            Route::post('charge/{order}', [PaymentController::class,'charge']);
        });

        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
