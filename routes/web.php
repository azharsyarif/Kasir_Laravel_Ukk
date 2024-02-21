<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PenggunaController;
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

Route::get('/home', [ProductController::class, 'index'])->name('home');
// Route::get('/create-discount-for-genre-a', [DiscountController::class, 'createDiscountForGenreA']);


Route::get('/admin/dashboard', [UserController::class, 'showAdminDashboard'])->middleware('auth')->name('admin.dashboard');



Route::get('/transactions/{id}', [ProductController::class, 'addDetailTransaction'])->name('transaction.detail')->middleware('auth');


Route::get('/pemesanan', [ProductController::class, 'pemesananIndex'])->middleware('auth');
Route::post('/create-product', [ProductController::class, 'create'])->name('create-product'); // Tambahkan route dengan nama create-product



Route::middleware(['auth', 'admin', 'check.session'])->group(function () {
    
Route::get('/pengguna', [AdminController::class, 'showAdminScreen'])->name('pengguna');
Route::get('/pengguna/add-pengguna', [AdminController::class, 'showAddPengguna'])->name('add-pengguna');
Route::post('/pengguna/add-pengguna', [AdminController::class, 'create'])->name('admins.data-pengguna.create');
Route::delete('/pengguna/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
Route::get('/admin/users/{id}/edit', [PenggunaController::class, 'editUser'])->name('admin.editUser');
Route::put('/admin/users/{id}', [PenggunaController::class, 'update'])->name('admin.updateUser');

    Route::get('/admin-product', [AdminController::class, 'showProductScreen'])->name('product');
    Route::get('/admin/add-product', [AdminController::class, 'showAddProduct'])->name('add-product');
    Route::post('/products/add', [AdminController::class, 'addProduct'])->name('products.add');
    Route::delete('/admin/product/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    Route::get('/admin/edit-product/{id}', [ProductController::class, 'showEditForm'])->name('edit-product');
    Route::put('/admin/edit-product/{id}', [ProductController::class, 'edit'])->name('update-product');

Route::get('/admin-discount', [DiscountController::class, 'showDiscountScreen'])->name('discount');
Route::get('/admin/add-discount', [DiscountController::class, 'showAddDiscount'])->name('add.discount');
Route::post('/admin/add-discount', [DiscountController::class, 'addDiscount'])->name('discount.add');
Route::delete('/admin/discount/{id}', [DiscountController::class, 'deleteDiscount'])->name('discount.delete');
Route::get('/admin/discount-detail/{id}', [DiscountController::class, 'showDiscountDetail'])->name('discount.detail');
Route::get('/admin/add-discount-genre', [DiscountController::class, 'addGenre'])->name('add.discount-genre');
Route::post('/admin/add-discount-genre/{discountId}', [DiscountController::class, 'addGenre'])->name('add.discount-genre');

Route::get('/admin-discount', [DiscountController::class, 'showDiscountScreen'])->name('discount');
Route::get('/admin/add-discount', [DiscountController::class, 'showAddDiscount'])->name('add.discount');
Route::post('/admin/add-discount', [DiscountController::class, 'addDiscount'])->name('discount.add');
Route::delete('/admin/discount/{id}', [DiscountController::class, 'deleteDiscount'])->name('discount.delete');
Route::get('/admin/discount-detail/{id}', [DiscountController::class, 'showDiscountDetail'])->name('discount.detail');
Route::get('/admin/add-discount-genre', [DiscountController::class, 'addGenre'])->name('add.discount-genre');
Route::post('/admin/add-discount-genre/{discountId}', [DiscountController::class, 'addGenre'])->name('add.discount-genre');
Route::delete('/admin/discount/{discount_id}/genre/{genre_id}', [DiscountController::class, 'deleteDiscountGenre'])->name('admin.discount.genre.delete');

Route::get('/admin-genre', [AdminController::class, 'showGenreScreen'])->name('genre');
Route::get('/admin/genre-add', [AdminController::class, 'showAddGenre'])->name('add-genre');
Route::post('/admin/genre-add', [AdminController::class, 'addGenre'])->name('add-genre');
Route::delete('/admin/genre/{id}', [AdminController::class, 'deleteGenre'])->name('admin.deleteGenre');
Route::put('/admin/edit-genre/{id}', [GenreController::class, 'updateGenre'])->name('update.genre');
Route::get('/admin/edit-genre/{id}', [GenreController::class, 'edit'])->name('edit.genre');

Route::get('/admin/show-users', [AdminController::class, 'showDataPengguna'])->name('admin.showUsers');





Route::middleware('auth')->post('/logout', [UserController::class, 'logout'])->name('logout');
});








