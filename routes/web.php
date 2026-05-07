<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Simulate Flash Sales
    $flashSales = \App\Models\Product::inRandomOrder()->take(6)->get();
    
    // Simulate Recommended
    $recommended = \App\Models\Product::inRandomOrder()->take(12)->get();
    
    // Group products by category
    $categories = \App\Models\Product::select('category')->distinct()->pluck('category');
    $categoryProducts = [];
    foreach ($categories as $category) {
        $categoryProducts[$category] = \App\Models\Product::where('category', $category)->take(6)->get();
    }
    
    return view('welcome', compact('flashSales', 'recommended', 'categoryProducts', 'categories'));
});

Route::get('/category/{category}', [\App\Http\Controllers\StoreController::class, 'category'])->name('product.category');
Route::get('/product/{product}', [\App\Http\Controllers\StoreController::class, 'show'])->name('product.show');

// Authentication Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Cart Routes (User/Guest, No Admin)
Route::middleware(['user'])->group(function () {
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
});

// Checkout & Orders Routes (User Only)
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'checkoutView'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout.process');
    Route::get('/user/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    
    // PayPal Routes
    Route::get('/paypal/payment/{order}', [App\Http\Controllers\PayPalController::class, 'payment'])->name('paypal.payment');
    Route::get('/paypal/success/{order}', [App\Http\Controllers\PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel/{order}', [App\Http\Controllers\PayPalController::class, 'cancel'])->name('paypal.cancel');
    // User Messaging (Read/Write)
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    // User Profile & Settings
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('user.profile');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('user.profile.password');

    // Rating Route
    Route::post('/rating', [App\Http\Controllers\RatingController::class, 'store'])->name('rating.store');
});

// Generic Auth Routes for both Admin and User
Route::middleware(['auth'])->group(function () {
    // Shared Message Deletion API
    Route::delete('/messages/{message}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/{message}/react', [App\Http\Controllers\MessageController::class, 'react'])->name('messages.react');
    Route::post('/messages/bulk-delete', [App\Http\Controllers\MessageController::class, 'bulkDestroy'])->name('messages.bulk_destroy');
    Route::post('/messages/thread-delete', [App\Http\Controllers\MessageController::class, 'threadDestroy'])->name('messages.thread_destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    // Placeholder Routes
    // Orders Routes
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('/users', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users.index');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('/analytics', [App\Http\Controllers\Admin\AdminController::class, 'analytics'])->name('analytics');


    Route::get('/earnings', [App\Http\Controllers\Admin\AdminController::class, 'earnings'])->name('earnings');

    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Admin Messaging Routes
    Route::get('/messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [App\Http\Controllers\Admin\MessageController::class, 'store'])->name('messages.store');

});

Route::get('/test-mail', function () {
    $email = request('email') ?? 'collinsnsubuga.ug@gmail.com';
    $preview = request('preview');

    if ($preview == 'registration') {
        $user = \App\Models\User::first();
        return new \App\Mail\UserRegistrationMail($user);
    }

    if ($preview == 'order') {
        $order = \App\Models\Order::first();
        if (!$order) return "No orders found to preview.";
        return new \App\Mail\OrderConfirmationMail($order);
    }

    try {
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\TestEmail());
        return "Test email sent to $email successfully! (Check storage/logs/laravel.log)";
    } catch (\Exception $e) {
        return "Failed to send email: " . $e->getMessage();
    }
});


