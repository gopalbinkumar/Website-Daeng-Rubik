<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\LearningMaterialController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Backend API untuk sistem Daeng Rubik.
| Sebagian besar endpoint di sini bersifat JSON-only dan akan
| dikonsumsi oleh UI yang sudah ada (produk, keranjang, checkout, admin).
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public product listing
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// Admin product management (bisa ditambah middleware auth/admin nanti)
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{product}', [ProductController::class, 'update']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);

// Cart
Route::get('/cart', [CartController::class, 'get']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::put('/cart/items/{item}', [CartController::class, 'updateQuantity']);
Route::delete('/cart/items/{item}', [CartController::class, 'remove']);
Route::delete('/cart', [CartController::class, 'clear']);

// Checkout
Route::post('/checkout/calculate', [CheckoutController::class, 'calculate']);
Route::post('/checkout', [CheckoutController::class, 'store']);

// Learning materials (video pembelajaran)
Route::get('/learning-materials', [LearningMaterialController::class, 'index']);
Route::get('/learning-materials/{learningMaterial}', [LearningMaterialController::class, 'show']);
Route::post('/learning-materials', [LearningMaterialController::class, 'store']);
Route::put('/learning-materials/{learningMaterial}', [LearningMaterialController::class, 'update']);
Route::delete('/learning-materials/{learningMaterial}', [LearningMaterialController::class, 'destroy']);

// Transactions & reports (admin)
Route::get('/admin/transactions', [TransactionController::class, 'index']);
Route::post('/admin/transactions/manual', [TransactionController::class, 'storeManual']);
Route::get('/admin/transactions/export', [TransactionController::class, 'exportPdf']);

