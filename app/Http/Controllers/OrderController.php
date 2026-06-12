<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $myUserId = Auth::id();

        $activeIncoming = Trade::with(['sender', 'senderItem', 'receiverItem'])
            ->where('receiver_id', $myUserId)->where('status', 'pending')->latest()->get();

        $activeOutgoing = Trade::with(['receiver', 'senderItem', 'receiverItem'])
            ->where('sender_id', $myUserId)->where('status', 'pending')->latest()->get();

        $acceptedTrades = Trade::with(['sender', 'receiver', 'senderItem', 'receiverItem'])
            ->where(function($q) use ($myUserId) {
                $q->where('receiver_id', $myUserId)->orWhere('sender_id', $myUserId);
            })->where('status', 'accepted')->latest()->get();

        $rejectedTrades = Trade::with(['sender', 'receiver', 'senderItem', 'receiverItem'])
            ->where(function($q) use ($myUserId) {
                $q->where('receiver_id', $myUserId)->orWhere('sender_id', $myUserId);
            })->where('status', 'rejected')->latest()->get();

        return view('user.order', compact('activeIncoming', 'activeOutgoing', 'acceptedTrades', 'rejectedTrades'));
    }

    public function clearRejected()
    {
        $myUserId = Auth::id();
        Trade::where(function($q) use ($myUserId) {
            $q->where('sender_id', $myUserId)->orWhere('receiver_id', $myUserId);
        })->where('status', 'rejected')->delete();

        return redirect()->back()->with('success', 'Semua riwayat tertolak berhasil dibersihkan.');
    }

    public function cancel(string $id)
    {
        $myUserId = Auth::id();

        // Cari transaksi berdasarkan ID, pastikan itu milik kita sebagai SENDER, dan statusnya masih pending
        $trade = Trade::where('id', $id)
                    ->where('sender_id', $myUserId)
                    ->where('status', 'pending')
                    ->firstOrFail();

        // Hapus data tawaran dari database
        $trade->delete();

        return redirect()->route('orders.index')->with('success', 'Tawaran barter berhasil dibatalkan.');
    }

   /**
     * ACCEPT: Menerima tawaran masuk dan menyapu bersih konflik transaksi (The Sweeper)
     */
    public function accept(string $id)
    {
        $myUserId = Auth::id();

        // 1. Cari transaksi yang akan di-accept (Pastikan hanya receiver yang bisa)
        $trade = \App\Models\Trade::where('id', $id)
                    ->where('receiver_id', $myUserId)
                    ->where('status', 'pending')
                    ->firstOrFail();

        // 2. Ubah status transaksi utama ini menjadi 'accepted'
        $trade->status = 'accepted';
        $trade->save();

        // 3. Kunci Fisik Barang (Ubah status di tabel items)
        $senderItem = \App\Models\Item::find($trade->sender_item_id);
        $receiverItem = \App\Models\Item::find($trade->receiver_item_id);

        if ($senderItem) {
            $senderItem->status = 'traded'; // Barang sudah laku
            $senderItem->save();
        }
        if ($receiverItem) {
            $receiverItem->status = 'traded'; // Barang sudah laku
            $receiverItem->save();
        }

        // 4. PENANGANAN MUTUAL MATCH (Tawaran Silang)
        // Jika lawan juga menawarkan hal yang persis sama ke kita, jadikan accepted juga!
        \App\Models\Trade::where('status', 'pending')
            ->where('sender_item_id', $trade->receiver_item_id)
            ->where('receiver_item_id', $trade->sender_item_id)
            ->update(['status' => 'accepted']);

        // 5. THE SWEEPER (Penyapuan Massal)
        // Cari SEMUA sisa transaksi 'pending' yang memperebutkan salah satu dari dua barang ini
        \App\Models\Trade::where('status', 'pending')
            ->where(function ($query) use ($trade) {
                $query->where('sender_item_id', $trade->sender_item_id)
                      ->orWhere('receiver_item_id', $trade->sender_item_id)
                      ->orWhere('sender_item_id', $trade->receiver_item_id)
                      ->orWhere('receiver_item_id', $trade->receiver_item_id);
            })
            ->update(['status' => 'rejected']); // Tolak secara paksa!

        return redirect()->route('orders.index')->with('success', 'Barter berhasil disetujui! Semua penawaran konflik telah otomatis ditolak.');
    }

    /**
     * REJECT: Menolak tawaran masuk
     */
    public function reject(string $id)
    {
        $myUserId = Auth::id();

        // Cari transaksi (Pastikan hanya receiver yang bisa)
        $trade = Trade::where('id', $id)
                    ->where('receiver_id', $myUserId)
                    ->where('status', 'pending')
                    ->firstOrFail();

        $trade->status = 'rejected';
        $trade->save();

        return redirect()->route('orders.index')->with('success', 'Tawaran barter telah ditolak.');
    }

/**
     * DELETE HISTORY: Menghapus riwayat transaksi yang sudah selesai (rejected/accepted)
     */
    public function destroyHistory(string $id)
    {
        $myUserId = \Illuminate\Support\Facades\Auth::id();

        // Bisa dihapus jika kita adalah sender atau receiver, dan statusnya sudah bukan pending
        $trade = \App\Models\Trade::where('id', $id)
                    ->where(function($query) use ($myUserId) {
                        $query->where('sender_id', $myUserId)
                              ->orWhere('receiver_id', $myUserId);
                    })
                    ->whereIn('status', ['rejected', 'accepted'])
                    ->firstOrFail();

        $trade->delete();

        return redirect()->back()->with('success', 'Riwayat transaksi berhasil dibersihkan.');
    }


    public function cancelDeal(string $id)
    {
        $myUserId = Auth::id();
        $trade = Trade::where('id', $id)
                    ->where(function($q) use ($myUserId) {
                        $q->where('sender_id', $myUserId)->orWhere('receiver_id', $myUserId);
                    })->where('status', 'accepted')->firstOrFail();

        // 1. Kembalikan kedua barang ke market
        \App\Models\Item::whereIn('id', [$trade->sender_item_id, $trade->receiver_item_id])
            ->update(['status' => 'available']);

        // 2. Lempar riwayat transaksi ke zona rejected
        $trade->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Kesepakatan dibatalkan. Barang kembali tersedia di Market.');
    }

}