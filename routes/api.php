<?php

use App\Http\Controllers\Api\V1\PromocodeController;
use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\BonusController;
use App\Http\Controllers\Api\V1\Category\CategoryController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Review\ReviewController;
use App\Http\Controllers\Api\V1\Product\ProductSearchController;
use App\Http\Controllers\Api\V1\Favorite\FavoriteController;
use App\Http\Controllers\Api\V1\CartItem\CartItemController;


Route::prefix('promo')->group(function () {
    Route::get('/check', [PromocodeController::class, 'index']);
    Route::get('/checkOtherPromos', [PromocodeController::class, 'checkServerPromos']);

    Route::get('/otherPromos', [PromocodeController::class, 'putOther']);
});

Route::get('/sanctum/csrf-cookie', static function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::middleware('auth:sanctum')->get('/user', function (\Illuminate\Http\Request $request) {
    return response()->json($request->user());
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
        Route::post('/logout', [AuthController::class, 'logout']);
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

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/search', [ProductSearchController::class, 'search']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
        Route::post('images/{product}', [ProductController::class, 'uploadImages']);
    });
});

Route::prefix('review')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [ReviewController::class, 'store']);
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->middleware('admin');
    });
    Route::get('/product/{product}', [ReviewController::class, 'index']);
});


Route::prefix('/favorite')->middleware('auth:sanctum')->group(function () {
    Route::post('/add', [FavoriteController::class, 'add']);
    Route::delete('/remove', [FavoriteController::class, 'remove']);
    Route::get('/', [FavoriteController::class, 'index']);
});

Route::prefix('cart')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CartItemController::class, 'index']);
    Route::post('/add', [CartItemController::class, 'addProduct']);
    Route::delete('/remove/{productId}', [CartItemController::class, 'removeProduct']);
    Route::delete('/clear', [CartItemController::class, 'clear']);
});
