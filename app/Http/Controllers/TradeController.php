<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Trade;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    public function propose(string $id)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // 🚨 PENCEGATAN: Jika nomor WhatsApp belum diisi, alihkan ke profil!
        if (empty($user->whatsapp_number)) {
            return redirect()->route('profile.edit')->with('error', 'Kamu wajib mengisi nomor WhatsApp di profil terlebih dahulu sebelum bisa melakukan barter!');
        }

        $targetItem = Item::with('user')->findOrFail($id);
        $myUserId = \Illuminate\Support\Facades\Auth::id();
        $targetItem = Item::with('user')->findOrFail($id);
        $myUserId = Auth::id();
        
        $alreadyOfferedForThisTarget = Trade::where('sender_id', $myUserId)
                                            ->where('receiver_item_id', $targetItem->id)
                                            ->where('status', 'pending')
                                            ->pluck('sender_item_id')
                                            ->toArray();

        // Tampilkan inventory, tapi KECUALIKAN barang yang sudah diajukan ke target ini
        $myInventory = Item::where('user_id', $myUserId)
                           ->where('status', 'available')
                           ->whereNotIn('id', $alreadyOfferedForThisTarget)
                           ->get();

        return view('trades.propose', compact('targetItem', 'myInventory'));
    }

    public function store(Request $request)
    {
        $myUserId = Auth::id();

        // 1. Validasi data yang masuk dari hidden input
        $request->validate([
            'sender_item_id' => 'required|exists:items,id',
            'receiver_item_id' => 'required|exists:items,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        // 2. Simpan ke database tabel trades
        Trade::create([
            'sender_id' => $myUserId,
            'receiver_id' => $request->receiver_id,
            'sender_item_id' => $request->sender_item_id,
            'receiver_item_id' => $request->receiver_item_id,
            'status' => 'pending',
        ]);

        // 3. Pindahkan user ke halaman Orders dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Penawaran barter berhasil dikirim!');
    }
}