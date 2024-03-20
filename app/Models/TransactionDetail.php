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

    // Temukan diskon yang berlaku
    $applicableDiscount = $product->findApplicableDiscount();

    // Jika diskon ditemukan, hitung harga total setelah diskon
    if ($applicableDiscount) {
        $discountAmount = $applicableDiscount->discount_amount;
        $totalPriceAfterDiscount = $totalPrice * (1 - ($discountAmount / 100));
    } else {
        // Jika tidak ada diskon, harga total setelah diskon sama dengan harga total sebelum diskon
        $totalPriceAfterDiscount = $totalPrice;
    }

    return $totalPriceAfterDiscount;
}

}
