<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected $redirectTo;

    public function __construct()
    {
        // Tidak ada pemanggilan redirectTo() di sini
    }

    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->role == 'admin') {
            return route('admin.dashboard');
        } elseif (Auth::check() && Auth::user()->role == 'kasir') {
            return route('home');
        } else {
            return '/';
        }
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Memanggil redirectTo() langsung di sini
        return redirect()->intended($this->redirectTo());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(\Illuminate\Http\Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
