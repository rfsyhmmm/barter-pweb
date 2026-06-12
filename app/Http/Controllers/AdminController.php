<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Trade;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalItems = Item::count();
        $successfulTrades = Trade::where('status', 'accepted')->count();

        $recentItems = Item::with('user')->latest()->take(5)->get();
        $usersList = User::where('role', 'user')->latest()->get();
        
        // Menarik data laporan masuk
        $reportsList = \App\Models\Report::with(['reporter', 'reportedUser', 'item'])->latest()->get();

        return view('admin.dashboard', compact('totalUsers', 'totalItems', 'successfulTrades', 'recentItems', 'usersList', 'reportsList'));
    }

    // Fungsi melihat detail user
    public function showUser(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-show', compact('user'));
    }

    // Fungsi melihat detail laporan
    public function showReport(string $id)
    {
        $report = \App\Models\Report::with(['reporter', 'reportedUser', 'item'])->findOrFail($id);
        return view('admin.report-show', compact('report'));
    }

    public function takedownItem(string $id)
    {
        $item = Item::findOrFail($id);
        
        // Optimasi Penyimpanan: Hapus gambar fisik jika ada
        if ($item->image_path) {
            $imagePath = public_path('images/' . $item->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Ubah status dan kosongkan path gambar
        $item->update([
            'status' => 'banned',
            'image_path' => null
        ]);
        
        Trade::where(function($q) use ($id) {
            $q->where('sender_item_id', $id)->orWhere('receiver_item_id', $id);
        })->where('status', 'pending')->update(['status' => 'rejected']);

        return redirect()->route('admin.dashboard')->with('success', 'Barang berhasil di-takedown permanen dan media dibersihkan.');
    }

    public function showItem(string $id)
    {
        // Menampilkan detail item terlepas dari statusnya (available/traded/banned)
        $item = Item::with('user')->findOrFail($id);
        
        // Mengambil riwayat transaksi terkait barang ini untuk kebutuhan audit
        $tradeHistory = Trade::with(['sender', 'receiver'])
            ->where('sender_item_id', $id)
            ->orWhere('receiver_item_id', $id)
            ->latest()
            ->get();

        return view('admin.item-show', compact('item', 'tradeHistory'));
    }

    public function toggleUserBan(string $id)
    {
        $user = User::findOrFail($id);
        
        // Cegah kelalaian Admin memblokir dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Tindakan ilegal. Kamu tidak dapat memblokir akunmu sendiri.');
        }

        // Toggle status: jika active jadi banned, jika banned jadi active
        $user->update([
            'status' => $user->status === 'active' ? 'banned' : 'active'
        ]);

        $message = $user->status === 'banned' 
            ? "Akun {$user->name} berhasil DIBLOKIR." 
            : "Akun {$user->name} berhasil DIPULIHKAN (Unban).";

        return redirect()->back()->with('success', $message);
    }
}