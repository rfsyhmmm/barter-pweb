<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // Menampilkan halaman form laporan
    public function create(string $id)
    {
        $item = Item::with('user')->findOrFail($id);
        
        // Jangan izinkan user melaporkan barangnya sendiri
        if ($item->user_id === Auth::id()) {
            return redirect()->route('home')->with('error', 'Kamu tidak bisa melaporkan barangmu sendiri.');
        }

        return view('report.create', compact('item'));
    }

    // Menyimpan data laporan ke database
    public function store(Request $request, string $id)
    {
        $request->validate([
            'reason' => 'required|string',
            'description' => 'required|string|max:1000',
        ]);

        $item = Item::findOrFail($id);

        Report::create([
            'reporter_id' => Auth::id(),
            'reported_user_id' => $item->user_id,
            'item_id' => $item->id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Laporan berhasil dikirim. Tim Moderator akan segera meninjaunya.');
    }
}