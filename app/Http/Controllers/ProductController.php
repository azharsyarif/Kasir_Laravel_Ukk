<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua data game dari database
        $product = Product::all();

        // Kirim data game ke tampilan Blade
        return view('home', ['products' => $product]);
    }

    // API FUNCTION
        public function view_api()
    {
        $products = Product::all();
        return view('view_api', ['products' => $products]);
    }

    public function pemesananIndex()
    {
        return view('pemesanan');
    }

    public function create(Request $request)
{
    // Validasi input
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.qty' => 'required|integer|min:1',
    ]);

    // Buat transaksi baru
    $transaction = Transaction::create([
        'user_id' => $request->user_id,
        'transaction_date' => Carbon::now(),
    ]);

    // Tambahkan detail transaksi untuk setiap produk
    foreach ($request->products as $product) {
        $productData = Product::findOrFail($product['product_id']); // Ubah menjadi product_id
        $totalPrice = $productData->harga * $product['qty'];
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product['product_id'], // Ubah menjadi product_id
            'qty' => $product['qty'],
            'harga_total' => $totalPrice,
        ]);
    }

    return response()->json([
        'message' => 'Transaction created successfully',
        'transaction' => $transaction
    ], 201);    
}




/* CREATE BESERTA LOG UNTUK LARAVEL LOG */
// public function create(Request $request)
// {
//     try {
//         // Validasi input
//         $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'products.*.product_id' => 'required|exists:products,id',
//             'products.*.qty' => 'required|integer|min:1',
//         ]);

//         // Buat transaksi baru
//         $transaction = Transaction::create([
//             'user_id' => $request->user_id,
//             'transaction_date' => Carbon::now(),
//         ]);

//         // Tambahkan detail transaksi untuk setiap produk
//         foreach ($request->products as $product) {
//             $productData = Product::findOrFail($product['product_id']);
//             $totalPrice = $productData->harga * $product['qty'];
//             TransactionDetail::create([
//                 'transaction_id' => $transaction->id,
//                 'product_id' => $product['product_id'],
//                 'qty' => $product['qty'],
//                 'harga_total' => $totalPrice,
//             ]);
//         }

//         // Log berhasil
//         \Log::info('Transaksi berhasil dibuat: ' . json_encode($transaction));

//         return response()->json([
//             'message' => 'Transaction created successfully',
//             'transaction' => $transaction
//         ], 201);   
//     } catch (\Exception $e) {
//         // Log kesalahan
//         \Log::error('Terjadi kesalahan dalam membuat transaksi: ' . $e->getMessage());

//         return response()->json([
//             'message' => 'Failed to create transaction'
//         ], 500);
//     } 
// }






//     public function creates(Request $request): RedirectResponse
// {
//     $data = $request->all();
//     // Validasi input
//     $request->validate([
//         'user_id' => 'required|exists:users,id',
//         'products' => 'required|array',
//         'products.*.product_id' => 'required|exists:products,id',
//         'products.*.qty' => 'required|integer|min:1',
//     ]);

//     // Buat transaksi baru
//     $transaction = Transaction::create([
//         'user_id' => $request->user_id,
//         'transaction_date' => Carbon::now(),
//     ]);

//     // Tambahkan detail transaksi untuk setiap produk
//     foreach ($request->products as $product) {
//         $productData = Product::findOrFail($product['product_id']);
//         $harga_total = $productData->harga * $product['qty'];
//         TransactionDetail::create([
//             'transaction_id' => $transaction->id,
//             'product_id' => $product['product_id'],
//             'qty' => $product['qty'],
//             'harga_total' => $harga_total,
//         ]);
//     }

//     // Redirect ke halaman tertentu setelah transaksi berhasil
//     return Redirect::route('products')->with('success', 'Transaksi berhasil!');
// }
    

    
}
