<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['nama_genre'];   
    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany(Product::class, 'detail_genres');
    }
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_genre_details')->withTimestamps();
    }
    public function discountDetails()
    {
        return $this->hasMany(DiscountDetail::class);
    }

    public function detailGenres()
{
    return $this->hasMany(DetailGenre::class, 'genre_id', 'id');
}
}
