<?php

namespace App\Http\Controllers;

use App\Models\discount;
use App\Models\DiscountDetail;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{

    public function indexApi()
{
    $discountDetails = DiscountDetail::all();
    return response()->json($discountDetails);
}
    
    public function showAddDiscount()
    {
        return view('admins.data-discount.add-discount');
    }

    public function showDiscountDetail($id)
{
    // Find the discount based on the provided ID
    $discount = Discount::findOrFail($id);

    // Fetch the genres associated with the discount
    $genres = $discount->genres;

    // Fetch available genres (for adding new genre)
    $availableGenres = Genre::all();

    // Pass the discount, its associated genres, and available genres to the view
    return view('admins.data-discount.detail-discount', compact('discount', 'genres', 'availableGenres'));
}

    // public function showAddDiscountForm()
    // {
    //     return view('admin.add-discount');
    // }

    public function showDiscountScreen()
{
    // Fetch discounts data
    $discounts = discount::all();
    
    // Pass the discounts data to the view
    return view('admins.discount', ['discounts' => $discounts]);
}

    public function addDiscount(Request $request)
    {
        // Validasi data dari permintaan
        $validator = Validator::make($request->all(), [
            'nama_discount' => 'required|string',
            'discount_amount' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan data discount baru ke database
        $discount = new Discount();
        $discount->nama_discount = $request->nama_discount;
        $discount->discount_amount = $request->discount_amount;
        $discount->start_date = $request->start_date;
        $discount->end_date = $request->end_date;
        $discount->save();

        // Redirect to a success page or back to the form with a success message
        return redirect()->route('discount')->with('success', 'Discount berhasil ditambahkan');
    }

    public function deleteDiscount($id)
    {
        $discount = discount::findOrFail($id);
        $discount->delete();

        return redirect()->route('discount')->with('success', 'User deleted successfully!');
    }


    public function addGenre(Request $request, $discountId)
{
    // Validate the request data
    $request->validate([
        'genre' => 'required|exists:genres,id',
    ]);

    // Find the discount based on the provided ID
    $discount = Discount::findOrFail($discountId);

    // Attach the selected genre to the discount
    $genre = Genre::findOrFail($request->genre);
    $discount->genres()->attach($genre);

    // Create discount detail
    DiscountDetail::create([
        'discount_id' => $discountId,
        'genre_id' => $genre->id,
    ]);

    // Redirect back to the detail page
    return redirect()->route('discount.detail', $discountId)->with('success', 'Genre added successfully.');
}


    public function index()
    {
        $discounts = Discount::with('details')->get();
        return response()->json($discounts);
    }

    public function deleteDiscountGenre(Request $request, $discount_id, $genre_id)
{
    // Temukan diskon berdasarkan ID
    $discount = Discount::findOrFail($discount_id);

    // Temukan genre berdasarkan ID
    $genre = Genre::findOrFail($genre_id);

    // Periksa apakah genre ada dalam diskon
    if (!$discount->genres->contains($genre)) {
        return redirect()->back()->with('error', 'Genre not found in discount.');
    }

    // Hapus genre dari diskon
    $discount->genres()->detach($genre);

    // Hapus detail diskon yang sesuai dari database
    DiscountDetail::where('discount_id', $discount_id)->where('genre_id', $genre_id)->delete();

    return redirect()->back()->with('success', 'Genre deleted successfully from discount.');
}
    public function createDiscountForGenreA()
    {
        // Membuat data diskon
        $discount = Discount::create([
            'nama_discount' => 'Diskon Genre A',
            'discount_amount' => 10, // Persentase diskon (misalnya 10%)
            'start_date' => now(),
            'end_date' => now()->addDays(30), // Diskon berlaku selama 30 hari
        ]);

        // Mendapatkan genre dengan ID 1 (misalnya, Genre A)
        $genreA = Genre::find(1);

        // Menambahkan diskon ke genre tersebut
        $genreA->discounts()->attach($discount->id);

        return "Diskon 'Diskon Genre A' telah ditambahkan ke Genre A.";
    }
}
