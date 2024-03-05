<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'transaction_id', 'qty','persentase_discount', 'harga_satuan'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateTotalPriceAfterDiscount()
    {
        // Ambil data produk
        $product = $this->product;

        // Hitung harga total sebelum diskon
        $totalPrice = $this->qty * $product->harga; // Menggunakan harga asli produk

        // Hitung diskon berdasarkan persentase diskon produk
        $discountAmount = $product->findApplicableDiscount()->discount_amount;

        // Hitung harga total setelah diskon
        $totalPriceAfterDiscount = $totalPrice * (1 - ($discountAmount / 100));

        return $totalPriceAfterDiscount;
    }
}
