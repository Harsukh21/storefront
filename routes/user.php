<?php

use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Auth\ResetPasswordController;
use App\Http\Controllers\User\Auth\TwoFactorController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\PaymentMethodController;
use App\Http\Controllers\User\ProductReviewController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReviewHelpfulController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
        Route::get('/register', [RegisterController::class, 'create'])->name('register');
        Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
        Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
        Route::get('/two-factor-challenge', [TwoFactorController::class, 'create'])->name('two-factor.challenge');
        Route::post('/two-factor-challenge', [TwoFactorController::class, 'store'])->name('two-factor.verify');
        Route::post('/two-factor-resend', [TwoFactorController::class, 'resend'])->name('two-factor.resend');
        Route::post('/two-factor-cancel', [TwoFactorController::class, 'cancel'])->name('two-factor.cancel');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/items', [WishlistController::class, 'store'])->name('wishlist.items.store');
        Route::delete('/wishlist/items/{item}', [WishlistController::class, 'destroy'])->name('wishlist.items.destroy');
        Route::post('/products/{slug}/reviews', [ProductReviewController::class, 'store'])->middleware('throttle:5,1')->name('products.reviews.store');
        Route::post('/reviews/{review}/helpful', [ReviewHelpfulController::class, 'store'])->middleware('throttle:20,1')->name('reviews.helpful.store');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');

        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

        Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
        Route::post('/addresses', [AddressController::class, 'store'])->middleware('throttle:10,1')->name('addresses.store');
        Route::put('/addresses/{address}', [AddressController::class, 'update'])->middleware('throttle:10,1')->name('addresses.update');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->middleware('throttle:10,1')->name('addresses.destroy');

        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->middleware('throttle:5,1')->name('payment-methods.store');
        Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->middleware('throttle:10,1')->name('payment-methods.update');
        Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->middleware('throttle:10,1')->name('payment-methods.destroy');
    });
});
