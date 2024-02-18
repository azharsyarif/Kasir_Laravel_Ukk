<?php

namespace App\Http\Controllers;

use App\Models\Genre;
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
        $transactions = Transaction::latest()->paginate(10); // Ambil data transaksi dari database
        $transactionDetails = TransactionDetail::all(); // Ambil semua detail transaksi
        return view('pemesanan', compact('transactions', 'transactionDetails'));
    }

        public function addDetailTransaction($id)
    {
        // Temukan transaksi berdasarkan ID
        $transactions = Transaction::find($id);

        if (!$transactions) {
            // Handle jika transaksi tidak ditemukan
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        // Temukan detail transaksi untuk transaksi ini
        $transactionDetails = TransactionDetail::where('transaction_id', $id)->get();
        
        // Kirim data transaksi dan detail transaksi ke view
        return view('transaction_detail', compact('transactions', 'transactionDetails'));
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

        // Hitung total harga pesanan sebelum diskon
        $totalPriceBeforeDiscount = 0;
        foreach ($request->products as $product) {
            $productData = Product::findOrFail($product['product_id']);
            $totalPriceBeforeDiscount += $productData->harga * $product['qty'];
        }

        // Tentukan ambang batas harga untuk mendapatkan diskon dan persentase diskon
        $discountThreshold = 1000000; // Ambang batas total harga untuk mendapatkan diskon
        $discountPercentage = 0.1; // Persentase diskon (10%)

        // Terapkan diskon jika total harga melebihi ambang batas
        if ($totalPriceBeforeDiscount >= $discountThreshold) {
            $totalPriceAfterDiscount = $totalPriceBeforeDiscount - ($totalPriceBeforeDiscount * $discountPercentage);
        } else {
            $totalPriceAfterDiscount = $totalPriceBeforeDiscount;
        }

        // Tambahkan detail transaksi untuk setiap produk
        foreach ($request->products as $product) {
            $productData = Product::findOrFail($product['product_id']);
            $totalPrice = $productData->harga * $product['qty'];

            // Hitung harga total setelah diskon
            $hargaTotal = $totalPriceAfterDiscount * $product['qty'];

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product['product_id'],
                'qty' => $product['qty'],
                'harga_total' => $hargaTotal,
            ]);
        }

        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $transaction,
            'discountThreshold' => $discountThreshold,
            'discountPercentage' => $discountPercentage
        ], 201);
    }


    public function showEditForm($id)
    {
        $product = Product::findOrFail($id); // Dapatkan data produk berdasarkan ID
        $genres = Genre::all(); // Dapatkan semua data genre

        return view('Admins.data-product.edit-product', compact('product', 'genres'));
    }


        public function edit(Request $request, $id)
        {
            $request->validate([
                'nama_product' => 'required|string|max:255',
                'platform' => 'required|string|max:255',
                'genre_id' => 'required|exists:genres,id'. $id,
                'harga' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional: Validasi gambar jika diubah
            ]);

            $product = Product::findOrFail($id); // Dapatkan data produk berdasarkan ID

            // Update data produk dengan data baru dari request
            $product->nama_product = $request->nama_product;
            $product->platform = $request->platform;
            $product->genre_id = $request->genre_id;
            $product->harga = $request->harga;

            // Optional: Jika ada gambar baru diunggah, simpan gambar yang baru
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('product_images');
                $product->image = $imagePath;
            }

            $product->save(); // Simpan perubahan

            return redirect()->route('product')->with('success', 'Product updated successfully');
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
