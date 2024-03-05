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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua data produk dari database
        $products = Product::all();
        
        // Kirim data produk ke tampilan Blade
        return view('home', ['products' => $products]);
    }
//     public function index()
// {
//     // Ambil semua data game dari database yang memiliki discount yang aktif pada hari ini
//     $products = Product::whereHas('genres.discounts', function ($query) {
//         $today = now()->toDateString();
//         $query->where('start_date', '<=', $today)
//             ->where('end_date', '>=', $today);
//     })->with(['genres' => function ($query) {
//         $query->with(['discounts' => function ($query) {
//             $today = now()->toDateString();
//             $query->where('start_date', '<=', $today)
//                 ->where('end_date', '>=', $today);
//         }]);
//     }])->get();
    
//     // Kirim data produk ke tampilan Blade
//     return view('home', ['products' => $products]);
// }



    // public function index(Request $request)
    // {
    //     $currentDate = now()->toDateString();

    //     // Mengambil semua data barang dengan eager loading untuk genres
    //     $barangData = Product::with(['genres' => function ($query) use ($currentDate) {
    //         $query->with(['discounts' => function ($subQuery) use ($currentDate) {
    //             $subQuery->where('start_date', '<=', $currentDate)
    //                 ->where('end_date', '>=', $currentDate);
    //         }]);
    //     }])->get();

    //     // Iterasi barangData untuk menghitung harga diskon jika berlaku
    //     foreach ($barangData as $barang) {
    //         // Menginisialisasi harga_diskon dengan harga asli
    //         $barang->harga_diskon = $barang->harga;

    //         $applicableDiscount = $barang->findApplicableDiscount();

    //         // Periksa apakah ada diskon yang berlaku
    //         if ($applicableDiscount) {
//             // Hapus simbolwe '%' dan konversi menjadi nilai numerik jika perlu
    //             $persentase_diskon = rtrim($applicableDiscount->persentase_diskon, '%');
    //             $persentase_diskon = (float) $persentase_diskon / 100; // Konversi menjadi pecahan
    //             // Hitung harga diskon
    //             $barang->harga_diskon = $barang->harga - ($barang->harga * $persentase_diskon);
    //         }
    //     }
    //     // Kembalikan data barang yang telah dimodifikasi
    //     return response()->json($barangData);
    // }

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
            'nama_product' => 'required|string',
            'platform' => 'required|string',
            'genre_id' => 'required|exists:genres,id',
            'harga' => 'required|numeric',
            'image' => 'nullable|image|max:2048', // optional, max 2MB
        ]);

        $product = Product::findOrFail($id);

        $product->nama_product = $request->nama_product;
        $product->platform = $request->platform;
        $product->harga = $request->harga;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        // Sync the genre
        $product->genres()->sync([$request->genre_id]);

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
