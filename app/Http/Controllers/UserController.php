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

    public function showAdminDashboard()
    {
        return view('Admins.admin-dashboard');
    }
    

    // METHOD FUNCTION
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'kasir') {
            return redirect()->route('home');
        }
    }

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
        // Cek izin pengguna untuk logout
        if (Auth::check()) {
            // Proses logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Redirect pengguna sesuai dengan role atau halaman utama
            return redirect()->route('login');
        } else {
            // Pengguna tidak terotentikasi, kembalikan 403 (Unauthorized)
            abort(403);
        }
    }
    
}
