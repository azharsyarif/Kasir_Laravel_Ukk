<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // METHOD VIEW
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showHomeScreen()
    {
        return view('home');
    }
    public function showAdminScreen()
    {
        // Call the method to fetch user data
        $userData = $this->showDataPengguna();
    
        // Logic to display the admin screen with user data
        return view('admin', ['penggunas' => $userData]);
    }
    

    // METHOD FUNCTION
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // Authentication passed
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('product');
        } elseif ($user->role === 'kasir') {
            return redirect()->route('products'); // or specify the URL directly
        }
    }

    // Authentication failed
    return redirect()->route('login')->with('error', 'Invalid credentials');
}
    

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        return back()->with('loginError', 'Login Failed!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    
}
