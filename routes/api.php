<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\UserSaleController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\UserSaleController as AdminUserSaleController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok', 'service' => 'campus-mall-api']));

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/home', [HomeController::class, 'index']);
Route::get('/categories', [ProductController::class, 'categories']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories/{category}/products', [ProductController::class, 'byCategory']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Shared messaging actions (user + admin)
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
    Route::post('/messages/{message}/react', [MessageController::class, 'react']);
    Route::post('/messages/bulk-delete', [MessageController::class, 'bulkDestroy']);

    Route::middleware('user')->group(function () {
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart', [CartController::class, 'add']);
        Route::patch('/cart/{cartItem}', [CartController::class, 'update']);
        Route::delete('/cart/{cartItem}', [CartController::class, 'remove']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}/paypal', [PayPalController::class, 'createPayment']);

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

        Route::post('/ratings', [RatingController::class, 'store']);

        Route::get('/messages', [MessageController::class, 'index']);
        Route::post('/messages', [MessageController::class, 'store']);

        Route::get('/trade-in/categories', [UserSaleController::class, 'categories']);
        Route::get('/trade-in', [UserSaleController::class, 'index']);
        Route::post('/trade-in', [UserSaleController::class, 'store']);
        Route::get('/trade-in/{userSale}', [UserSaleController::class, 'show']);
        Route::post('/trade-in/{userSale}/accept', [UserSaleController::class, 'accept']);
        Route::post('/trade-in/{userSale}/reject', [UserSaleController::class, 'reject']);
        Route::delete('/trade-in/{userSale}', [UserSaleController::class, 'destroy']);
    });

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard']);
        Route::get('/analytics', [AdminDashboardController::class, 'analytics']);
        Route::get('/earnings', [AdminDashboardController::class, 'earnings']);

        Route::get('/products', [AdminProductController::class, 'index']);
        Route::post('/products', [AdminProductController::class, 'store']);
        Route::get('/products/{product}', [AdminProductController::class, 'show']);
        Route::put('/products/{product}', [AdminProductController::class, 'update']);
        Route::post('/products/{product}', [AdminProductController::class, 'update']); // multipart
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);
        Route::delete('/product-images/{image}', [AdminProductController::class, 'destroyImage']);

        Route::get('/orders', [AdminOrderController::class, 'index']);
        Route::get('/orders/{order}', [AdminOrderController::class, 'show']);
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);

        Route::get('/users', [AdminUserController::class, 'index']);
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);
        Route::get('/profile', [AdminUserController::class, 'profile']);
        Route::put('/profile', [AdminUserController::class, 'updateProfile']);

        Route::get('/messages', [AdminMessageController::class, 'index']);
        Route::get('/messages/threads', [AdminMessageController::class, 'threads']);
        Route::get('/messages/{user}', [AdminMessageController::class, 'show']);
        Route::post('/messages/{user}', [AdminMessageController::class, 'store']);

        Route::get('/trade-in', [AdminUserSaleController::class, 'index']);
        Route::get('/trade-in/{userSale}', [AdminUserSaleController::class, 'show']);
        Route::patch('/trade-in/{userSale}/offer', [AdminUserSaleController::class, 'makeOffer']);
        Route::patch('/trade-in/{userSale}/status', [AdminUserSaleController::class, 'updateStatus']);

        Route::get('/settings', [AdminSettingController::class, 'index']);
        Route::put('/settings', [AdminSettingController::class, 'update']);
    });
});
