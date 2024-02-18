<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    // Jika pengguna terautentikasi dan sedang dalam sesi
    if (Auth::check() && $request->session()->has('last_activity')) {
        $lastActivity = $request->session()->get('last_activity');
        $timeoutDuration = 15; // Durasi timeout dalam menit

        // Jika waktu terakhir aktivitas melebihi durasi timeout
        if (time() - $lastActivity > $timeoutDuration * 60) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')->with('error', 'Your session has timed out. Please log in again.');
        } else {
            // Perbarui waktu terakhir aktivitas
            $request->session()->put('last_activity', time());
        }
    }

    return $next($request);
}
}
