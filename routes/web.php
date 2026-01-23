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
Route::view('/belajar', 'pages.learn')->name('learn');
Route::view('/tentang', 'pages.about')->name('about');
Route::view('/kontak', 'pages.contact')->name('contact');
