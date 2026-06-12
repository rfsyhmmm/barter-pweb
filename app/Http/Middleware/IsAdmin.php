<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cegat jika belum login atau bukan admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // PERBAIKAN: Ubah dari /home menjadi /
            return redirect('/')->with('error', 'Akses ditolak. Kamu bukan Admin.');
        }

        return $next($request);
    }
}