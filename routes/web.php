<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::get('/home', [UserController::class, 'showHomeScreen'])->name('home');
Route::get('/home', [ProductController::class, 'index'])->middleware('auth')->name('products');

Route::get('/pemesanan', [ProductController::class, 'pemesananIndex']);
Route::post('/create-product', [ProductController::class, 'create'])->name('create-product'); // Tambahkan route dengan nama create-product

Route::get('/pengguna', [AdminController::class, 'showAdminScreen'])->name('pengguna');
Route::get('/pengguna/add-pengguna', [AdminController::class, 'showAddPengguna'])->name('add-pengguna');
Route::post('/pengguna/add-pengguna', [AdminController::class, 'create'])->name('admins.data-pengguna.create');
Route::delete('/pengguna/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

Route::get('/admin-product', [AdminController::class, 'showProductScreen'])->name('product');
Route::get('/admin/add-product', [AdminController::class, 'showAddProduct'])->name('add-product');
Route::post('/products/add', [AdminController::class, 'addProduct'])->name('products.add');
Route::delete('/admin/product/{id}', [AdminController::class, 'deleteProduct'])->name('admin.deleteProduct');

Route::get('/admin/show-users', [AdminController::class, 'showDataPengguna'])->name('admin.showUsers');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

