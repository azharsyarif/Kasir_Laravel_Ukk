<?php

namespace App\Http\Controllers;

use App\Models\discount;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
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
        $discount->genres()->attach($request->genre);

        // Redirect back to the detail page
        return redirect()->route('discount.detail', $discountId)->with('success', 'Genre added successfully.');
    }

    public function index()
    {
        $discounts = Discount::with('details')->get();
        return response()->json($discounts);
    }
}
