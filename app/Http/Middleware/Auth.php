<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
                // Cek apakah pengguna sudah terautentikasi
                if (!Auth::check()) {
                    // Jika pengguna belum login, arahkan ke halaman login
                    return redirect()->route('login');
                }
        
                // Jika pengguna sudah login, lanjutkan ke proses berikutnya
                return $next($request);
    }
}
