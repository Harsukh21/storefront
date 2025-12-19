<?php

use App\Http\Controllers\FrontWebsite\BrandDirectoryController;
use App\Http\Controllers\FrontWebsite\CartController;
use App\Http\Controllers\FrontWebsite\CategoryDirectoryController;
use App\Http\Controllers\FrontWebsite\CheckoutController;
use App\Http\Controllers\FrontWebsite\HomeController;
use App\Http\Controllers\FrontWebsite\ProductController;
use App\Http\Controllers\FrontWebsite\ProductQuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('front.home');
Route::view('/test/theme-toggle-demo', 'test.theme-toggle-demo')->name('test.theme-toggle-demo');
Route::view('/about', 'frontwebsite.pages.about')->name('front.about');
Route::view('/contact', 'frontwebsite.pages.contact')->name('front.contact');
Route::view('/privacy-policy', 'frontwebsite.pages.privacy')->name('front.privacy');
Route::view('/cookie-policy', 'frontwebsite.pages.cookie')->name('front.cookie');

Route::get('/categories', [CategoryDirectoryController::class, 'index'])->name('front.categories');
Route::get('/categories/{slug}', [CategoryDirectoryController::class, 'show'])->name('front.categories.show');
Route::get('/brands', [BrandDirectoryController::class, 'index'])->name('front.brands');
Route::get('/brands/{slug}', [BrandDirectoryController::class, 'show'])->name('front.brands.show');

Route::get('/products', [ProductController::class, 'index'])->name('front.products');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('front.products.show');
Route::post('/products/{slug}/questions', [ProductQuestionController::class, 'store'])->middleware(['auth', 'throttle:10,1'])->name('front.products.questions.store');

Route::prefix('cart')->name('front.cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/', [CartController::class, 'store'])->name('store');
    Route::put('/items/{item}', [CartController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [CartController::class, 'destroy'])->name('items.destroy');
    Route::get('/mini', [CartController::class, 'miniCart'])->name('mini');
});

Route::prefix('checkout')->name('front.checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->middleware('throttle:5,1')->name('store');
    Route::get('/confirmation/{orderNumber}', [CheckoutController::class, 'confirmation'])->name('confirmation');
});

require __DIR__.'/user.php';
require __DIR__.'/admin.php';
