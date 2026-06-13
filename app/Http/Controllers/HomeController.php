<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tangkap kata kunci pencarian
        $search = $request->input('search');

        // 2. Mulai query dasar
        $query = Item::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->where('status', 'available');

        // 3. Sembunyikan barang milik sendiri
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // 4. Terapkan filter pencarian (Judul, Deskripsi, Kategori, atau NAMA USER)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 5. Gunakan paginate
        $items = $query->latest()->paginate(3);

        return view('home', compact('items', 'search'));
    }
}