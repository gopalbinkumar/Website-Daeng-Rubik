<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'pages.home')->name('home');

Route::view('/produk', 'pages.products')->name('products');
Route::view('/event', 'pages.events')->name('events');
Route::view('/event/daftar', 'pages.event-register')->name('events.register');
Route::view('/belajar', 'pages.learn')->name('learn');
Route::view('/tentang', 'pages.about')->name('about');
Route::view('/kontak', 'pages.contact')->name('contact');

// Auth Routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::view('/lupa-password', 'auth.forgot-password')->name('forgot');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::view('/produk', 'admin.products.index')->name('products.index');
    Route::view('/event', 'admin.events.index')->name('events.index');
    Route::view('/materi', 'admin.learn.index')->name('learn.index');
    Route::view('/admin', 'admin.admins.index')->name('admins.index');
    Route::view('/pengaturan', 'admin.settings')->name('settings');
    Route::get('/logout', function () {
        return redirect()->route('admin.dashboard');
    })->name('logout');
});
