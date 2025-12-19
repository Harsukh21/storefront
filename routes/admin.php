<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Auth\TwoFactorController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductQuestionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ReviewModerationController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\TaxRateController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductOptionController;
use App\Http\Controllers\Admin\InventoryAdjustmentController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
        Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
        Route::get('/two-factor-challenge', [TwoFactorController::class, 'create'])->name('two-factor.challenge');
        Route::post('/two-factor-challenge', [TwoFactorController::class, 'store'])->name('two-factor.verify');
        Route::post('/two-factor-resend', [TwoFactorController::class, 'resend'])->name('two-factor.resend');
        Route::post('/two-factor-cancel', [TwoFactorController::class, 'cancel'])->name('two-factor.cancel');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::prefix('catalog')->name('catalog.')->group(function () {
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

            Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
            Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
            Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
            Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
            Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
            Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

            // Product Images
            Route::get('/products/{product}/images', [ProductController::class, 'images'])->name('products.images');
            Route::post('/products/{product}/images', [ProductController::class, 'storeImage'])->name('products.images.store');
            Route::put('/products/{product}/images/{image}', [ProductController::class, 'updateImage'])->name('products.images.update');
            Route::delete('/products/{product}/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
            Route::post('/products/{product}/images/{image}/set-primary', [ProductController::class, 'setPrimaryImage'])->name('products.images.set-primary');
            Route::post('/products/{product}/images/reorder', [ProductController::class, 'reorderImages'])->name('products.images.reorder');

            // Product Variants
            Route::get('/products/{product}/variants', [ProductVariantController::class, 'index'])->name('products.variants');
            Route::post('/products/{product}/variants', [ProductVariantController::class, 'store'])->name('products.variants.store');
            Route::put('/products/{product}/variants/{variant}', [ProductVariantController::class, 'update'])->name('products.variants.update');
            Route::delete('/products/{product}/variants/{variant}', [ProductVariantController::class, 'destroy'])->name('products.variants.destroy');

            // Product Attributes
            Route::get('/products/{product}/attributes', [ProductAttributeController::class, 'index'])->name('products.attributes');
            Route::post('/products/{product}/attributes', [ProductAttributeController::class, 'store'])->name('products.attributes.store');
            Route::put('/products/{product}/attributes/{attribute}', [ProductAttributeController::class, 'update'])->name('products.attributes.update');
            Route::delete('/products/{product}/attributes/{attribute}', [ProductAttributeController::class, 'destroy'])->name('products.attributes.destroy');

            // Product Options
            Route::get('/products/{product}/options', [ProductOptionController::class, 'index'])->name('products.options');
            Route::post('/products/{product}/options/types', [ProductOptionController::class, 'storeType'])->name('products.options.types.store');
            Route::put('/products/{product}/options/types/{optionType}', [ProductOptionController::class, 'updateType'])->name('products.options.types.update');
            Route::delete('/products/{product}/options/types/{optionType}', [ProductOptionController::class, 'destroyType'])->name('products.options.types.destroy');
            Route::post('/products/{product}/options/types/{optionType}/values', [ProductOptionController::class, 'storeValue'])->name('products.options.values.store');
            Route::put('/products/{product}/options/types/{optionType}/values/{optionValue}', [ProductOptionController::class, 'updateValue'])->name('products.options.values.update');
            Route::delete('/products/{product}/options/types/{optionType}/values/{optionValue}', [ProductOptionController::class, 'destroyValue'])->name('products.options.values.destroy');
        });

        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
        Route::get('/inventory/adjustments', [InventoryAdjustmentController::class, 'index'])->name('inventory.adjustments.index');
        Route::post('/inventory/adjustments', [InventoryAdjustmentController::class, 'store'])->name('inventory.adjustments.store');

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('show');
            Route::put('/{orderNumber}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        });

        Route::prefix('shipments')->name('shipments.')->group(function () {
            Route::post('/orders/{orderNumber}', [ShipmentController::class, 'store'])->name('store');
            Route::put('/{shipment}', [ShipmentController::class, 'update'])->name('update');
        });

        Route::prefix('refunds')->name('refunds.')->group(function () {
            Route::post('/orders/{orderNumber}', [RefundController::class, 'store'])->name('store');
            Route::put('/{refund}', [RefundController::class, 'update'])->name('update');
        });

        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [PaymentController::class, 'index'])->name('index');
            Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        });

        Route::prefix('discounts')->name('discounts.')->group(function () {
            Route::get('/', [DiscountController::class, 'index'])->name('index');
            Route::get('/create', [DiscountController::class, 'create'])->name('create');
            Route::post('/', [DiscountController::class, 'store'])->name('store');
            Route::get('/{discount}/edit', [DiscountController::class, 'edit'])->name('edit');
            Route::put('/{discount}', [DiscountController::class, 'update'])->name('update');
            Route::delete('/{discount}', [DiscountController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('tax-rates')->name('tax-rates.')->group(function () {
            Route::get('/', [TaxRateController::class, 'index'])->name('index');
            Route::get('/create', [TaxRateController::class, 'create'])->name('create');
            Route::post('/', [TaxRateController::class, 'store'])->name('store');
            Route::get('/{taxRate}/edit', [TaxRateController::class, 'edit'])->name('edit');
            Route::put('/{taxRate}', [TaxRateController::class, 'update'])->name('update');
            Route::delete('/{taxRate}', [TaxRateController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        });

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
            Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [ReviewModerationController::class, 'index'])->name('index');
            Route::put('/{review}', [ReviewModerationController::class, 'update'])->name('update');
            Route::delete('/{review}', [ReviewModerationController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/', [ProductQuestionController::class, 'index'])->name('index');
            Route::post('/{question}/answer', [ProductQuestionController::class, 'answer'])->name('answer');
            Route::put('/{question}', [ProductQuestionController::class, 'update'])->name('update');
            Route::delete('/{question}', [ProductQuestionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
            Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::put('/{user}/password', [UserManagementController::class, 'updatePassword'])->name('password.update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('admins')->name('admins.')->group(function () {
            Route::get('/', [AdminManagementController::class, 'index'])->name('index');
            Route::get('/create', [AdminManagementController::class, 'create'])->name('create');
            Route::post('/', [AdminManagementController::class, 'store'])->name('store');
            Route::get('/{admin}', [AdminManagementController::class, 'show'])->name('show');
            Route::get('/{admin}/edit', [AdminManagementController::class, 'edit'])->name('edit');
            Route::put('/{admin}', [AdminManagementController::class, 'update'])->name('update');
            Route::put('/{admin}/password', [AdminManagementController::class, 'updatePassword'])->name('password.update');
            Route::delete('/{admin}', [AdminManagementController::class, 'destroy'])->name('destroy');
        });
    });
});
