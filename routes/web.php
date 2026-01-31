<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;


Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/produk', [ProductController::class, 'userIndex'])
    ->name('products');

//halaman pembelajaran
Route::prefix('belajar')->name('learn.')->group(function () {
    Route::view('/', 'pages.learn.index')->name('index');
    Route::view('/video', 'pages.learn.video')->name('video');
    Route::view('/modul', 'pages.learn.module')->name('module');
});


Route::view('/event', 'pages.events')->name('events');
Route::view('/event/daftar', 'pages.event-register')->name('events.register');
Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::view('/keranjang', 'pages.cart')->name('cart');
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


// =================
//   ADMIN ROUTES
// =================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::view('/', 'admin.dashboard')->name('dashboard');

        Route::prefix('produk')
            ->controller(ProductController::class)
            ->group(function () {

                Route::get('/', 'adminIndex')->name('products.index');
                Route::post('/', 'store')->name('products.store');
                Route::put('/{product}', 'update')->name('products.update');
                Route::delete('/{product}', 'destroy')->name('products.destroy');

            });

        Route::prefix('event')
            ->controller(EventController::class)
            ->group(function () {

                Route::get('/', 'index')->name('events.index');
                Route::post('/', 'store')->name('events.store');
                Route::put('/{event}', 'update')->name('events.update');
                Route::delete('/{event}', 'destroy')->name('events.destroy');

            });

        Route::view('/materi', 'admin.learn.index')->name('learn.index');
        Route::view('/admin', 'admin.admins.index')->name('admins.index');
        Route::view('/pengaturan', 'admin.settings')->name('settings');
        Route::view('/laporan/penjualan', 'admin.reports.sales')->name('reports.sales');

        Route::post('/logout', [UserController::class, 'logout'])
            ->name('logout');


    });

