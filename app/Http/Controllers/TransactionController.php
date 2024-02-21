<?php

namespace App\Http\Controllers;

use App\Models\DiscountDetail;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

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
            TransactionDetail::create([
                'transaction_id' => $transactionId,
                'product_id' => $product['productId'],
                'qty' => $product['quantity'],
                'harga_total' => $product['productPrice'] * $product['quantity'],
            ]);
        }

        // Beri respons sukses ke klien
        return response()->json(['message' => 'Order successfully stored'], 200);
    }
}
