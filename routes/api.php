<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Account routes
    Route::prefix('accounts')->group(function () {
        Route::post('/', [AccountController::class, 'create']);
        Route::get('/', [AccountController::class, 'list']);
        Route::post('/{account}/deposit', [AccountController::class, 'deposit']);
        Route::get('/{account}/balance', [AccountController::class, 'getBalance']);
        Route::post('/apply-interest', [AccountController::class, 'applyMonthlyInterest']);
    });
});
