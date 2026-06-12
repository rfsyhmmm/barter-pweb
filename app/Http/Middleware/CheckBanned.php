<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user login tapi statusnya banned, tendang paksa
        if (Auth::check() && Auth::user()->status === 'banned') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('error', 'Akses ditolak. Akun kamu telah diblokir oleh Moderator.');
        }

        return $next($request);
    }
}