<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // registration
    public function register(Request $request)
    {
        // validasi email @its.ac.id atau @student.its.ac.id
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@(student\.)?its\.ac\.id$/i' 
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.regex' => 'Pendaftaran gagal! Gunakan email resmi institusi (@student.its.ac.id atau @its.ac.id).',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Simpan user baru ke database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Otomatis login setelah berhasil mendaftar
        Auth::login($user);
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang di BarterPlace.');
    }

    // 3. Tampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 4. Proses Masuk Akun (Login)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Coba lakukan autentikasi ke database
        if (Auth::attempt($credentials)) {
            // Cek status sesaat setelah berhasil login
            if (Auth::user()->status === 'banned') {
                Auth::logout(); // Langsung tendang keluar lagi
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akses ditolak! Akun kamu telah diblokir oleh Admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended(route('home'));
        }
        
        // Jika gagal (email/pw salah), kembalikan dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ])->onlyInput('email');
    }

    // 5. Proses Keluar Akun (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Kamu telah berhasil keluar.');
    }
}