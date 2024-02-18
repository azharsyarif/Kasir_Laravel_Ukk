<?php

namespace App\Http\Controllers;

use App\Models\discount;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Closure;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{

    // VIEW FUNCTION
    public function showDataProduct()
    {
        $products = Product::all();
        
        // Return the fetched user data
        return $products;
    }

    
    public function showAddPengguna()
    {
        return view('admins.data-pengguna.add-pengguna');
    }
    public function showAddDiscount()
    {
        return view('admins.data-discount.add-discount');
    }
    public function showAddGenre()
    {
        return view('admins.data-genre.add-genre');
    }
    public function showAddProduct()
    {

        $genres = $this->showDataGenre();

        return view('admins.data-product.add-product', ['genres' => $genres]);
    }


    public function showDiscountDetail($id)
    {
        // Mencari discount berdasarkan id_discount
        $discount = Discount::findOrFail($id);

        // Mengirim data discount ke view discount_detail.blade.php
        return view('admins.data-discount.detail-discount', compact('discount'));
    }
    


    
    public function showAdminScreen()
    {
        // Call the method to fetch user data
        $userData = $this->showDataPengguna();
    
        // Logic to display the admin screen with user data
        return view('admins.pengguna', ['penggunas' => $userData]);
    }


    public function showDiscountScreen()
    {
        // Fetch discounts data
        $discounts = Discount::all();
        
        // Pass the discounts data to the view
        return view('admins.discount', ['discounts' => $discounts]);
    }

    public function showProductScreen()
    {
        // Call the method to fetch user data
        $userData = $this->showDataProduct();
    
        // Logic to display the admin screen with user data
        return view('admins.product', ['products' => $userData]);
    }
        public function showGenreScreen()
    {
        $genres = Genre::all();
        
        return view('admins.genre', ['genres' => $genres]);
    }



    public function showDataPengguna()
    {
        $penggunas = User::all();
        
        // Return the fetched user data
        return $penggunas;
    }
    public function showDataDiscount()
    {
        $discounts = discount::all();
        
        // Return the fetched user data
        return $discounts;
    }
    public function showDataGenre()
    {
        $genres = Genre::all();
        
        // Return the fetched user data
        return $genres;
    }

    // API
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_product' => 'required|string',
            'image' => 'required|string',
            'platform' => 'required|string', // Make sure platform is required
            'genre' => 'required|string',
            'harga' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        $product = new Product(); // Create a new Product instance
        $product->nama_product = $request->nama_product;
        $product->image = $request->image;
        $product->platform = $request->platform;
        $product->genre = $request->genre;
        $product->harga = $request->harga;
        $product->save(); // Save the product to the database
    
        // Check if the product was successfully saved
        if ($product->wasRecentlyCreated) {
            return response()->json(['message' => 'Product berhasil dibuat', 'product' => $product], 201);
        } else {
            return response()->json(['error' => 'Terjadi kesalahan saat membuat produk.'], 500);
        }
    }

    public function create(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:kasir,admin', // Add other roles here
        ]);
    
        // Create new user instance
        $user = new User();
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->role = $validatedData['role'];
        
        // Save the user
        $user->save();
    
        // Redirect to admin page with success message
        return redirect()->route('pengguna')->with('success', 'User created successfully!');
    }

        public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('pengguna')->with('success', 'User deleted successfully!');
    }


    public function addProduct(Request $request)
{
    // Validasi data dari permintaan
    $validator = Validator::make($request->all(), [
        'nama_product' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image file
        'platform' => 'required|string|max:255',
        'genre_id' => 'required|exists:genres,id', // Pastikan genre_id ada dalam tabel genres
        'harga' => 'required|string',
    ]);

    if ($validator->fails()) {
        // Jika validasi gagal, kembali ke halaman sebelumnya dengan pesan kesalahan
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('product_images', 'public');

        // Simpan data produk baru ke database
        $product = new Product();
        $product->nama_product = $request->nama_product;
        $product->image = $imagePath;
        $product->platform = $request->platform;
        $product->genre_id = $request->genre_id;
        $product->harga = $request->harga;
        $product->save();

        // Redirect to a success page or back to the form with a success message
        return redirect()->route('product')->with('success', 'Product berhasil dibuat');
    } else {
        // Jika gambar tidak diunggah, kembali ke halaman sebelumnya dengan pesan kesalahan
        return redirect()->back()->with('error', 'Gambar produk wajib diunggah.');
    }
}
    


    

public function deleteProduct($id)
    {
        $products = Product::findOrFail($id);
        $products->delete();

        return redirect()->route('product')->with('success', 'User deleted successfully!');
    }


    public function addGenre(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_genre' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $genre = new Genre();
        $genre->nama_genre = $request->nama_genre;
        $genre->save();
    
        if ($genre) {
            return redirect()->route('genre')->with('success', 'Genre berhasil ditambahkan!');
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah genre.');
        }
    }
    
    public function deleteGenre($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();

        return redirect()->route('genre')->with('success', 'User deleted successfully!');
    }


    public function showAddDiscountForm()
    {
        return view('admin.add-discount');
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
    
}
