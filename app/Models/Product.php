<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nama_product', 'image', 'platform', 'harga'];

   public function genres()
    {
        return $this->belongsToMany(Genre::class, 'detail_genres');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    // public function discount()
    // {
    //     return $this->belongsTo(Discount::class);
    // }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class);
    // }

    public function findApplicableDiscount()
    {
        $today = now()->toDateString();

        $applicableDiscount = $this->genres()
            ->whereHas('discounts', function ($query) use ($today) {
                $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })
            ->with(['discounts' => function ($query) use ($today) {
                $query->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today)
                    ->orderBy('discount_amount', 'desc');
            }])
            ->get()
            ->pluck('discounts')
            ->collapse()
            ->unique('id')
            ->first();

        return $applicableDiscount;
    }
    public function detailGenres()
{
    return $this->hasMany(DetailGenre::class, 'product_id', 'id');
}

//     public function index()
// {
//     $products = Product::all();
//     return view('home', ['products' => $products]);
// }


}
