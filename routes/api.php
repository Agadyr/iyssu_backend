<?php

use App\Http\Controllers\Api\V1\PromocodeController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\BonusController;
use App\Http\Controllers\Api\V1\Category\CategoryController;

Route::prefix('promo')->group(function () {
    Route::get('/check', [PromocodeController::class, 'index']);
    Route::get('/checkOtherPromos', [PromocodeController::class, 'checkServerPromos']);

    Route::get('/otherPromos', [PromocodeController::class, 'putOther']);
});

Route::prefix('auth')->group(function () {

    Route::prefix('/forgot-password')->group(function () {
        Route::post('/send-otp', [AuthController::class, 'sendResetOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtpReset']);
        Route::post('/reset', [AuthController::class, 'resetPassword']);
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::post('/send-confirmation-otp', [AuthController::class, 'sendConfirmationOtp']);
        Route::post('/verify-email', [AuthController::class, 'verifyOtpEmail']);
    });
});


Route::prefix('profile')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [UserProfileController::class, 'me']);
    Route::put('/update', [UserProfileController::class, 'update']);
});

Route::prefix('bonuses')->middleware('auth:sanctum')->group(function () {
    Route::get('/points', [BonusController::class, 'getBonusPoints']);
    Route::get('/history', [BonusController::class, 'getBonusHistory']);

    Route::middleware('admin')->group(function () {
        Route::post('/add/{user}', [BonusController::class, 'addBonusPoints']);
        Route::post('/deduct/{user}', [BonusController::class, 'deductBonusPoints']);
    });
});

Route::prefix('/category')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::get('/{category}', [CategoryController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });
});
