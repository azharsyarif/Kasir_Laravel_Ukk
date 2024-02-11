<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{

    // VIEW FUNCTION
    public function showAddPengguna()
    {
        return view('admins.data-pengguna.add-pengguna');
    }
    public function showAddProduct()
    {
        return view('admins.data-product.add-product');
    }
    public function showAdminScreen()
    {
        // Call the method to fetch user data
        $userData = $this->showDataPengguna();
    
        // Logic to display the admin screen with user data
        return view('admins.pengguna', ['penggunas' => $userData]);
    }
    public function showProductScreen()
    {
        // Call the method to fetch user data
        $userData = $this->showDataProduct();
    
        // Logic to display the admin screen with user data
        return view('admins.product', ['products' => $userData]);
    }
    public function showDataPengguna()
    {
        $penggunas = User::all();
        
        // Return the fetched user data
        return $penggunas;
    }
    public function showDataProduct()
    {
        $products = Product::all();
        
        // Return the fetched user data
        return $products;
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
    $validator = Validator::make($request->all(), [
        'nama_product' => 'required|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image file
        'platform' => 'required|string', 
        'genre' => 'required|string',
        'harga' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        // Simpan gambar dan dapatkan pathnya
        $imagePath = $request->file('image')->store('product_images', 'public');

        // Simpan path gambar ke dalam database
        $product = new Product(); // Create a new Product instance
        $product->nama_product = $request->nama_product;
        $product->image = $imagePath; // Simpan path gambar ke dalam kolom 'image'
        $product->platform = $request->platform;
        $product->genre = $request->genre;
        $product->harga = $request->harga;
        $product->save(); // Save the product to the database

        // Check if the product was successfully saved
        if ($product->wasRecentlyCreated) {
            return redirect()->route('product')->with('success', 'Product berhasil dibuat');
        } else {
            // Jika gagal menyimpan, hapus gambar yang telah diunggah
            Storage::disk('public')->delete($imagePath);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah produk.');
        }
    } else {
        return redirect()->back()->with('error', 'Gambar produk wajib diunggah.');
    }
}

public function deleteProduct($id)
    {
        $products = Product::findOrFail($id);
        $products->delete();

        return redirect()->route('product')->with('success', 'User deleted successfully!');
    }
    
}
