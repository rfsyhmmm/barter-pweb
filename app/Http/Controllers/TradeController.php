<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    /**
     * Menampilkan daftar penawaran masuk dan keluar (Halaman Orders)
     */
    public function index()
    {
        $authId = Auth::id();

        // Ambil tawaran dari orang lain yang masuk ke barang kita (Sebagai Penjual)
        $incomingTrades = Trade::where('receiver_id', $authId)
            ->with(['sender', 'receiverItem', 'senderItem'])
            ->latest()
            ->get();

        // Ambil tawaran yang kita ajukan ke barang orang lain (Sebagai Pembeli)
        $outgoingTrades = Trade::where('sender_id', $authId)
            ->with(['receiver', 'receiverItem', 'senderItem'])
            ->latest()
            ->get();

        return view('user.order', compact('incomingTrades', 'outgoingTrades'));
    }

    /**
     * Menampilkan form pengajuan trade (Fungsi bawaan halaman propose)
     */
    public function create(string $itemId)
    {
        // Cek apakah Pembeli sudah punya nomor WA
        if (empty(Auth::user()->whatsapp_number)) {
            return redirect()->route('profile.edit')->with('error', '⚠️ Akses ditolak: Silakan lengkapi Nomor WhatsApp kamu di profil terlebih dahulu agar penjual bisa menghubungimu nanti.');
        }

        $targetItem = Item::with('user')->findOrFail($itemId);
        
        // Ambil barang milik sendiri yang berstatus available untuk ditawarkan
        $myInventory = Item::where('user_id', Auth::id())
            ->where('status', 'available')
            ->get();

        return view('trades.propose', compact('targetItem', 'myInventory'));
    }

    /**
     * STORE: Menyimpan pengajuan penawaran baru (Vanilla, Partial, Full Pay)
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_item_id' => 'required|exists:items,id',
            'receiver_id'      => 'required|exists:users,id',
            'sender_item_id'   => 'nullable|exists:items,id',
            'amount'           => 'nullable|numeric|min:0',
        ]);

        $amount = $request->amount ?? 0;
        $senderItemId = $request->sender_item_id;

        if ($request->receiver_id == Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa menawar barang milikmu sendiri!');
        }

        if (empty($senderItemId) && $amount <= 0) {
            return back()->with('error', 'Transaksi gagal: Pilih barang tawaran ATAU masukkan nominal pembayaran.');
        }

        $existingTrade = Trade::where('sender_id', Auth::id())
            ->where('receiver_item_id', $request->receiver_item_id)
            ->whereIn('status', ['pending', 'negotiating'])
            ->first();

        if ($existingTrade) {
            return back()->with('error', 'Kamu sudah memiliki penawaran yang berjalan untuk barang ini.');
        }

        Trade::create([
            'sender_id'        => Auth::id(),
            'receiver_id'      => $request->receiver_id,
            'sender_item_id'   => $senderItemId,
            'receiver_item_id' => $request->receiver_item_id,
            'amount'           => $amount,
            'status'           => 'pending',
        ]);

        return redirect()->route('orders.index')->with('success', 'Penawaran berhasil dikirim! Silakan pantau di tab Tawaran Keluar.');
    }

    /**
     * STEP 1 ACCEPTANCE: Penjual setuju lanjut diskusi ke WhatsApp
     */
    public function negotiate(string $id)
    {
        // [TAMBAHAN BARU] Cek apakah Penjual sudah punya nomor WA & Rekening
        $user = Auth::user();
        if (empty($user->whatsapp_number) || empty($user->bank_name) || empty($user->bank_account_number)) {
            return redirect()->route('profile.edit')->with('error', '⚠️ Tindakan dicegah: Lengkapi Nomor WhatsApp dan Data Rekening Pencairan di profil kamu terlebih dahulu sebelum memproses pesanan.');
        }

        $trade = Trade::where('receiver_id', Auth::id())->findOrFail($id);
        $trade->update(['status' => 'negotiating']);

        return redirect()->back()->with('success', 'Status berubah menjadi Negosiasi. Tombol WhatsApp kini telah terbuka!');
    }

    /**
     * REJECT: Penjual menolak penawaran pembeli
     */
    public function reject(string $id)
    {
        $trade = Trade::where('receiver_id', Auth::id())->findOrFail($id);
        $trade->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Penawaran berhasil ditolak.');
    }

    /**
     * STEP 2 ACCEPTANCE: Penjual menerbitkan Invoice Tagihan (Deal di WA)
     */
    public function invoice(Request $request, string $id)
    {
        $request->validate([
            'payment_method' => 'required|in:COD,Transfer'
        ]);

        $trade = Trade::where('receiver_id', Auth::id())->findOrFail($id);
        
        $trade->update([
            'status' => 'awaiting_payment',
            'payment_method' => $request->payment_method
        ]);

        // LOCK BARANG: Ubah status barang target menjadi 'draft' atau 'booked' 
        // agar tidak muncul lagi di halaman Explore market selama proses bayar/COD
        if ($trade->receiverItem) {
            $trade->receiverItem->update(['status' => 'draft']);
        }

        return redirect()->back()->with('success', 'Invoice tagihan berhasil dibuat! Menunggu tindakan pembayaran/pertemuan dari pembeli.');
    }

    /**
     * UPLOAD PROOF: Pembeli mengunggah bukti transfer manual (Escrow)
     */
    public function uploadProof(Request $request, string $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $trade = Trade::where('sender_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $imageName = time() . '_proof.' . $request->payment_proof->extension();
            $request->payment_proof->move(public_path('images/proofs'), $imageName);
            
            $trade->update([
                'payment_proof' => $imageName,
                'status' => 'paid' // Otomatis set ke paid untuk kelancaran testing prototype
            ]);
        }

        return redirect()->back()->with('success', 'Bukti transfer berhasil dikirim. Status berubah menjadi Paid!');
    }

    /**
     * CANCEL: Pembeli membatalkan penawarannya sendiri sebelum direspon penjual
     */
    public function cancel(string $id)
    {
        $trade = Trade::where('sender_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);
            
        // Hapus tawaran dari database agar tidak nyangkut di history
        $trade->delete();

        return redirect()->back()->with('success', 'Tawaran kamu berhasil dibatalkan.');
    }

    /**
     * FULFILLMENT COMPLETE: Pembeli mengonfirmasi barang diterima & auto-reject aktif
     */
    public function complete(string $id)
    {
        $trade = Trade::where('sender_id', Auth::id())->findOrFail($id);
        
        $trade->update(['status' => 'completed']);

        // Ubah status kepemilikan barang target secara permanen di pasar
        if ($trade->receiverItem) {
            $trade->receiverItem->update(['status' => 'traded']);
        }

        // Jika skenario barter/tukar tambah, kunci juga barang milik pembeli
        if ($trade->senderItem) {
            $trade->senderItem->update(['status' => 'traded']);
        }

        // mekanisme SWEEPER 
        // Ambil ID barang-barang yang terlibat dan sudah laku ini
        $itemIdsToSweep = array_filter([$trade->receiver_item_id, $trade->sender_item_id]);

        if (!empty($itemIdsToSweep)) {
            // Tolak paksa (Auto-Reject) SEMUA transaksi pending/negotiating lain milik orang lain
            // yang memperebutkan salah satu dari barang yang baru saja laku ini
            Trade::whereIn('status', ['pending', 'negotiating'])
                ->where('id', '!=', $trade->id) // Jangan sentuh transaksi yang barusan sukses ini
                ->where(function ($query) use ($itemIdsToSweep) {
                    $query->whereIn('sender_item_id', $itemIdsToSweep)
                          ->orWhereIn('receiver_item_id', $itemIdsToSweep);
                })
                ->update(['status' => 'rejected']);
        }

        return redirect()->back()->with('success', '🎉 Transaksi selesai! Penawaran dari user lain untuk barang ini telah otomatis ditolak sistem.');
    }
}