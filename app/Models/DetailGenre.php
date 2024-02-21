<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailGenre extends Model
{
    use HasFactory;

    protected $table = 'detail_genres';

    protected $fillable = ['genre_id', 'product_id'];

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id');
}


public function genre()
{
    return $this->belongsTo(Genre::class, 'genre_id', 'id');
}
}
