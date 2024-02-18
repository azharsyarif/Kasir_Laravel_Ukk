<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::post('/create-product', [ProductController::class, 'create'])->name('nama_rute_berhasil');

Route::get('/view-api', [ProductController::class, 'view_api'])->name('view_api');

Route::post('/create-product', [ProductController::class, 'create'])->name('create-product');

Route::post('/create-product', [TransactionController::class, 'store'])->name('store-order');

// Route::post('/addproduct', [AdminController::class, 'store']);

Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');
