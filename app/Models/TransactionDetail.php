<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'transaction_id', 'qty', 'harga_total'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
