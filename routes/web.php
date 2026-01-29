<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/produk', [ProductController::class, 'userIndex'])
    ->name('products');
Route::view('/materivideo', 'pages.learn-video')->name('learn.video');
Route::view('/materimodul', 'pages.learn-module')->name('learn.module');

Route::view('/event', 'pages.events')->name('events');
Route::view('/event/daftar', 'pages.event-register')->name('events.register');
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::view('/keranjang', 'pages.cart')->name('cart');
Route::view('/belajar', 'pages.learn')->name('learn.index');
Route::view('/tentang', 'pages.about')->name('about');
Route::view('/kontak', 'pages.contact')->name('contact');

// Auth Routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [UserController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.post');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/register', [UserController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->name('register.post');
    Route::view('/lupa-password', 'auth.forgot-password')->name('forgot');
});

// Admin Routes
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::view('/', 'admin.dashboard')->name('dashboard');
//     Route::view('/produk', 'admin.products.index')->name('products.index');
//     Route::view('/event', 'admin.events.index')->name('events.index');
//     Route::view('/materi', 'admin.learn.index')->name('learn.index');
//     Route::view('/admin', 'admin.admins.index')->name('admins.index');
//     Route::view('/pengaturan', 'admin.settings')->name('settings');
//     Route::view('/laporan/penjualan', 'admin.reports.sales')->name('reports.sales');
//     Route::get('/logout', function () {
//         return redirect()->route('admin.dashboard');
//     })->name('logout');
// });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::view('/', 'admin.dashboard')->name('dashboard');

        Route::get('/produk', [ProductController::class, 'adminIndex'])
            ->name('products.index');

        Route::post('/produk', [ProductController::class, 'store'])
            ->name('products.store');

        Route::put('/produk/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/produk/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');

        Route::view('/event', 'admin.events.index')->name('events.index');
        Route::view('/materi', 'admin.learn.index')->name('learn.index');
        Route::view('/admin', 'admin.admins.index')->name('admins.index');
        Route::view('/pengaturan', 'admin.settings')->name('settings');
        Route::view('/laporan/penjualan', 'admin.reports.sales')->name('reports.sales');

        Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout'])
            ->name('logout');


    });

