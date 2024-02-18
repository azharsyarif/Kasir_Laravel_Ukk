<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountDetail extends Model
{
    use HasFactory;

    protected $fillable = ['id','discount_id', 'genre_id'];


    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    
}
