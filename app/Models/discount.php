<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_discount', 'discount_amount', 'start_date', 'end_date',
    ];

        public function genres()
    {
        return $this->belongsToMany(Genre::class, 'discount_genre_details')->withTimestamps();
    }

    public function details()
    {
        return $this->hasMany(DiscountDetail::class);
    }

}
