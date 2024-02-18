<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'transaction_date'];


    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class); // tambahkan relasi belongsTo
    }
}
