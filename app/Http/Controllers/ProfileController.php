<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Memproses pembaharuan data profil
     */
    public function update(Request $request)
    {
        // GANTI BARIS INI:
        $user = \App\Models\User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'nullable|string|min:10|max:15|regex:/^[0-9]+$/',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'whatsapp_number.regex' => 'Nomor WhatsApp harus berupa angka saja.',
            'profile_picture.max' => 'Ukuran foto profil maksimal adalah 2MB.'
        ]);

        // Proses upload gambar profil jika ada
        if ($request->hasFile('profile_picture')) {
            $imageName = time() . '_profile.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('images/profiles'), $imageName);
            $user->profile_picture = $imageName;
        }

        $user->name = $request->name;
        $user->whatsapp_number = $request->whatsapp_number;
        
        // Garis merah pada save() di bawah ini pasti akan langsung hilang!
        $user->save(); 

        return redirect()->route('profile.edit')->with('success', 'Profil kamu berhasil diperbarui!');
    }
}