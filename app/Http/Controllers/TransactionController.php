<?php

namespace App\Http\Controllers;

use App\Models\DiscountDetail;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;

class TransactionController extends Controller
{
    // <!-- PDF -->

public function generatePdf(Request $request)
{
    // Pastikan pengguna telah diautentikasi
    if (Auth::check()) {
        // Retrieve the last transaction ID from the Transaction model
        $lastTransactionId = Transaction::latest('id')->value('id');

        // Periksa apakah ada transaksi terakhir
        if ($lastTransactionId) {
            // Ambil detail transaksi dari database berdasarkan ID transaksi terakhir
            $transaction = Transaction::with('details')->findOrFail($lastTransactionId);
            $cashierName = Auth::user()->name; // Dapatkan nama kasir

            // Inisialisasi array untuk menyimpan nama produk dan kuantitas
            $productName = [];
            $quantity = [];

            // Hitung total harga dari detail transaksi dan simpan nama produk dan kuantitas
            $totalPrice = 0;
            foreach ($transaction->details as $detail) {
                $subtotal = $detail->qty * $detail->product->harga;
                $totalPrice += $subtotal;

                // Simpan nama produk dan kuantitas ke dalam array
                $productName[] = $detail->product->nama_product;
                $quantity[] = $detail->qty;
            }

            // Tentukan persentase diskon berdasarkan total harga
            $totalDiscountPercentage = ($totalPrice > 1000000) ? 2 : 0;

            // Hitung diskon total
            $totalDiscount = ($totalDiscountPercentage / 100) * $totalPrice;

            // Hitung total harga setelah diskon
            $totalPriceAfterDiscount = $totalPrice - $totalDiscount;

            // Tampilkan halaman nota PDF dengan data transaksi dan persentase diskon total
            $view = view('nota_pdf', compact('transaction', 'cashierName', 'totalDiscount', 'totalDiscountPercentage', 'totalPrice', 'totalPriceAfterDiscount', 'productName', 'quantity'))->render();

            // Konfigurasi Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            // Buat instance Dompdf
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($view);

            // Atur ukuran dan orientasi halaman
            $dompdf->setPaper('A4', 'portrait');

            // Render PDF (outputnya langsung disimpan dalam variabel)
            $dompdf->render();

            // Kembalikan file PDF kepada pengguna
            return $dompdf->stream('nota.pdf', ['Attachment' => false]);
        } else {
            // Jika tidak ada transaksi, beri respons dengan pesan error
            return redirect()->back()->with('error', 'No transaction found.');
        }
    } else {
        // Jika pengguna belum diautentikasi, redirect ke halaman login
        return redirect()->route('login')->with('error', 'Please login to view the transaction.');
    }
}



    // VIEW FUNCTION

    public function indexTransaction()
{
    $transactions = Transaction::with('user', 'details')->get(); 
    
    // Inisialisasi array untuk menyimpan nama kasir
    $cashierNames = [];

    // Iterasi melalui setiap transaksi
    foreach ($transactions as $transaction) {
        if ($transaction->user) {
            // Jika ada user yang terkait dengan transaksi, ambil namanya
            $cashierName = $transaction->user->username;
        } else {
            // Jika tidak ada user terkait, tandai sebagai Unknown
            $cashierName = 'Unknown';
        }
        // Tambahkan nama kasir ke dalam array
        $cashierNames[] = $cashierName;
    }

    // Mengambil semua transaksi dari database
    return view('Admins.transaction', compact('transactions', 'cashierNames'));
}


    public function showNota(Request $request)
{
    // Retrieve the last transaction with user information
    $lastTransaction = Transaction::latest('id')->with('user')->first();

    // Check if the last transaction exists
    if ($lastTransaction) {
        // Check if the user relationship exists
        if ($lastTransaction->user) {
            // Get the cashier's full name from the last transaction
            $cashierName = $lastTransaction->user->username;
        } else {
            // If user relationship does not exist, set cashierName to Unknown
            $cashierName = 'Unknown';
        }

        // Get other transaction details
        $totalPrice = 0;
        foreach ($lastTransaction->details as $detail) {
            $subtotal = $detail->qty * $detail->product->harga;
            $totalPrice += $subtotal;
        }
        
        // Calculate total discount percentage
        $totalDiscountPercentage = ($totalPrice > 1000000) ? 2 : 0;

        // Calculate total discount
        $totalDiscount = ($totalDiscountPercentage / 100) * $totalPrice;

        // Calculate total price after discount
        $totalPriceAfterDiscount = $totalPrice - $totalDiscount;

        // Return the view with transaction details
        return view('nota', compact('lastTransaction', 'cashierName', 'totalPrice', 'totalDiscountPercentage', 'totalPriceAfterDiscount'));
    } else {
        // If no transaction found, return with an error message
        return redirect()->back()->with('error', 'No transaction found.');
    }
}




    public function index()
    {
        $transactions = Transaction::with('details')->latest()->get();
        
        // Check if the number of transactions is greater than 10
        if ($transactions->count() > 10) {
            $transactions = Transaction::with('details')->latest()->paginate(10);
        }
        
        return view('transactions.index', compact('transactions'));
    }



    public function store(Request $request)
{
    // Validasi data yang diterima
    $request->validate([
        'user_id' => 'required|integer',
        'products' => 'required|array',
        'products.*.productId' => 'required|integer',
        'products.*.productName' => 'required|string',
        'products.*.productPrice' => 'required|numeric',
        'products.*.quantity' => 'required|integer|min:1',
    ]);

    // Mendapatkan ID transaksi yang baru
    $transactionId = Transaction::create([
        'user_id' => $request->user_id,
        'transaction_date' => now(), // Sesuaikan dengan format tanggal yang kamu inginkan
    ])->id;

    // Proses menyimpan data produk ke tabel transaction_details
    foreach ($request->products as $product) {
        // Ambil harga asli produk
        $productPrice = Product::findOrFail($product['productId'])->harga;

        // Hitung harga total sebelum diskon
        $totalPrice = $productPrice * $product['quantity'];

        // Hitung harga total setelah diskon tambahan
        $discountedPrice = $totalPrice * (1 - ($request->additional_discount_percentage / 100));

        // Ambil data diskon produk jika ada
        $productDiscount = Product::findOrFail($product['productId'])->findApplicableDiscount();
        $discountPercentage = $productDiscount ? $productDiscount->discount_amount : 0;

        TransactionDetail::create([
            'transaction_id' => $transactionId,
            'product_id' => $product['productId'],
            'qty' => $product['quantity'],
            'harga_satuan' => $productPrice, // Gunakan harga asli produk
            'harga_total' => $totalPrice,
            'persentase_discount' => $discountPercentage, // Simpan persentase diskon produk
            'additional_discount_percentage' => $request->additional_discount_percentage ?? 0,
            // Simpan harga total setelah diskon tambahan
            'harga_total_setelah_diskon' => $discountedPrice,
        ]);
    }

    // Beri respons sukses ke klien
    return response()->json(['message' => 'Order successfully stored'], 200);
}

    
}
