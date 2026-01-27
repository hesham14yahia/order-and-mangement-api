<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\OrderController;

Route::group(["prefix" => "v1"], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::group(['prefix'=> 'orders'], function () {
            Route::post('create', [OrderController::class, 'create']);
            Route::post('update/{order}', [OrderController::class,'update']);
            Route::post('confirm/{order}', [OrderController::class,'confirm']);
        });
    });
});
