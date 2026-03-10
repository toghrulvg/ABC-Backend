<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return 'welcome';
});

Route::prefix('api')->withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth.token');

    Route::middleware('auth.token')->group(function () {
        Route::get('/accounts', [AccountController::class, 'getAll']);
        Route::get('/cards', [CardController::class, 'getAll']);
        Route::get('/transactions', [TransactionController::class, 'getAll']);
        Route::get('/beneficiaries', [BeneficiaryController::class, 'getAll']);
        Route::get('/transfers', [TransferController::class, 'getAll']);
        Route::get('/notifications', [NotificationController::class, 'getAll']);
    });
});
