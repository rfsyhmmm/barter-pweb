<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil internal
     */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Memproses pembaruan data profil (WA, Rekening, dll)
     */
    public function update(Request $request)
    {
        // Tambahkan komentar PHPDoc ini agar Intelephense tahu tipe data aslinya
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
        ]);

        // Garis merah di bawah 'update' sekarang akan menghilang!
        $user->update([
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menampilkan Profil Publik & Etalase Barang Aktif
     */
    public function show(string $id)
    {
        // 1. Ambil data user target atau lempar 404 jika tidak ditemukan
        $user = User::findOrFail($id);

        // 2. Proteksi Keamanan: Jika akun diblokir admin, kunci akses profil publiknya
        if ($user->status === 'banned') {
            return redirect()->route('home')->with('error', 'Profil pengguna ini tidak tersedia karena akun sedang dibekukan oleh sistem.');
        }

        // 3. Ambil semua barang milik user ini yang statusnya 'available' (Sudah di Market)
        $items = Item::where('user_id', $user->id)
            ->where('status', 'available')
            ->latest()
            ->get();

        // 4. Hitung akumulasi transaksi sukses (Poin Reputasi Komunitas)
        $successfulTrades = Trade::where('status', 'completed')
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })->count();

        // 5. Kirimkan seluruh variabel ke view profile.show
        return view('profile.show', compact('user', 'items', 'successfulTrades'));
    }
}