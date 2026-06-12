@extends('layouts.app')

@section('title', 'Daftar Penawaran & Pesanan - BarterPlace')

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Management Orders</h1>
        <p class="text-gray-500 text-sm mt-1">Pantau dan eksekusi pengajuan barter, tukar tambah, atau pembelian barang
            di sini.</p>
    </div>

    <div class="flex border-b border-gray-200 mb-8 gap-6" id="orderTabs" role="tablist">
        <button
            class="tab-btn pb-4 text-sm font-bold border-b-2 border-green-600 text-green-600 transition cursor-pointer"
            data-target="#tawaran-masuk">
            📥 Tawaran Masuk (Sebagai Penjual)
        </button>
        <button
            class="tab-btn pb-4 text-sm font-medium border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition cursor-pointer"
            data-target="#tawaran-keluar">
            📤 Tawaran Keluar (Sebagai Pembeli)
        </button>
    </div>

    <div id="tab-contents">

        <div id="tawaran-masuk" class="tab-panel space-y-6">
            @forelse($incomingTrades as $trade)
            <div
                class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">

                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 flex-1 w-full min-w-0">

                    <div
                        class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100 w-full sm:w-auto sm:flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-white shrink-0 border border-gray-200">
                            <img src="{{ asset('images/items/' . $trade->receiverItem->image_path) }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <span
                                class="text-[9px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md uppercase">Barang
                                Target</span>
                            <h4 class="font-bold text-gray-900 text-sm truncate">{{ $trade->receiverItem->title }}</h4>
                        </div>
                    </div>

                    <div class="text-gray-400 font-bold text-xl px-2 rotate-90 sm:rotate-0 shrink-0">
                        @if(!$trade->sender_item_id) 💳 @else ⇄ @endif
                    </div>

                    <div
                        class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100 w-full sm:w-auto sm:flex-1 min-w-0">
                        @if($trade->senderItem)
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-white shrink-0 border border-gray-200">
                            <img src="{{ asset('images/items/' . $trade->senderItem->image_path) }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <span
                                class="text-[9px] font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded-md uppercase">Tawaranmu</span>
                            <h4 class="font-bold text-gray-900 text-sm truncate">{{ $trade->senderItem->title }}</h4>
                            @if($trade->amount > 0)
                            <p class="text-xs font-bold text-green-600 mt-0.5">+ Rp
                                {{ number_format($trade->amount, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        @else
                        <div
                            class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold shrink-0 shadow-inner">
                            💰</div>
                        <div class="min-w-0">
                            <span
                                class="text-[9px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md uppercase">Beli
                                Langsung</span>
                            <h4 class="font-black text-gray-900 text-base truncate">Rp
                                {{ number_format($trade->amount, 0, ',', '.') }}</h4>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="lg:text-right shrink-0">
                    <p class="text-xs text-gray-400 font-medium mb-1">Oleh: <span
                            class="text-gray-700 font-bold">{{ $trade->sender->name }}</span></p>

                    @include('user.partials.status-badge', ['status' => $trade->status])
                </div>

                <div
                    class="w-full lg:w-auto flex flex-col sm:flex-row gap-2 shrink-0 border-t lg:border-t-0 pt-4 lg:pt-0 border-gray-100">
                    @if($trade->status === 'pending')
                    <form action="{{ route('trade.negotiate', $trade->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full sm:w-auto bg-green-600 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-green-700 transition cursor-pointer">
                            💬 Lanjut Diskusi WA
                        </button>
                    </form>
                    <form action="{{ route('trade.reject', $trade->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf @method('PATCH')
                        <button type="submit" onclick="return confirm('Tolak penawaran ini?')"
                            class="w-full sm:w-auto bg-white border border-gray-200 text-red-600 font-bold text-xs px-5 py-3 rounded-xl hover:bg-red-50 transition cursor-pointer">
                            Tolak
                        </button>
                    </form>

                    @elseif($trade->status === 'negotiating')
                    <a href="https://wa.me/{{ $trade->sender->whatsapp_number }}" target="_blank"
                        class="w-full sm:w-auto bg-emerald-500 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-emerald-600 transition flex items-center justify-center gap-1.5 shadow-xs">
                        📱 Hubungi Via WA
                    </a>

                    <form action="{{ route('trade.invoice', $trade->id) }}" method="POST"
                        class="w-full sm:w-auto flex gap-1">
                        @csrf @method('PATCH')
                        @if($trade->amount > 0)
                        <select name="payment_method" required
                            class="bg-gray-50 border border-gray-200 rounded-xl px-2 text-xs font-bold focus:outline-none">
                            <option value="Transfer">Transfer Escrow</option>
                            <option value="COD">Tunai (COD)</option>
                        </select>
                        @else
                        <input type="hidden" name="payment_method" value="COD">
                        @endif
                        <button type="submit"
                            class="w-full bg-black text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-gray-800 transition cursor-pointer">
                            🤝 Buat Tagihan
                        </button>
                    </form>

                    @elseif($trade->status === 'awaiting_payment')
                    <span
                        class="text-xs font-bold text-gray-400 bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 block text-center">⏳
                        Menunggu Pembayaran Pembeli</span>

                    @elseif($trade->status === 'paid')
                    <div
                        class="bg-green-50 border border-green-200 text-green-800 p-3 rounded-xl text-xs font-medium max-w-[260px]">
                        🎉 Pembayaran Escrow Valid. Silakan lakukan ketemuan (COD) di kampus untuk serah terima barang!
                    </div>

                    @elseif($trade->status === 'completed')
                    <div
                        class="bg-gray-50 border border-gray-100 text-gray-500 p-3 rounded-xl text-xs font-medium max-w-[260px]">
                        ✅ Selesai. Dana/Barang telah berpindah tangan. Admin akan mencairkan ke rekening Anda jika
                        melibatkan tunai.
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white border border-gray-100 p-12 rounded-3xl text-center text-gray-400 text-sm">Belum ada
                penawaran masuk dari pengguna lain.</div>
            @endforelse
        </div>

        <div id="tawaran-keluar" class="tab-panel space-y-6 hidden">
            @forelse($outgoingTrades as $trade)
            <div
                class="bg-white rounded-3xl border border-gray-100 shadow-xs p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">

                <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 flex-1 w-full min-w-0">

                    <div
                        class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100 w-full sm:w-auto sm:flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-white shrink-0 border border-gray-200">
                            <img src="{{ asset('images/items/' . $trade->receiverItem->image_path) }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <span
                                class="text-[9px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md uppercase">Barangmu</span>
                            <h4 class="font-bold text-gray-900 text-sm truncate">{{ $trade->receiverItem->title }}</h4>
                        </div>
                    </div>

                    <div class="text-gray-400 font-bold text-xl px-2 rotate-90 sm:rotate-0 shrink-0">
                        @if(!$trade->sender_item_id) 💳 @else ⇄ @endif
                    </div>

                    <div
                        class="flex items-center gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-100 w-full sm:w-auto sm:flex-1 min-w-0">
                        @if($trade->senderItem)
                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-white shrink-0 border border-gray-200">
                            <img src="{{ asset('images/items/' . $trade->senderItem->image_path) }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <span
                                class="text-[9px] font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded-md uppercase">Ditukar
                                Dengan</span>
                            <h4 class="font-bold text-gray-900 text-sm truncate">{{ $trade->senderItem->title }}</h4>
                            @if($trade->amount > 0)
                            <p class="text-xs font-bold text-green-600 mt-0.5">+ Rp
                                {{ number_format($trade->amount, 0, ',', '.') }}</p>
                            @endif
                        </div>
                        @else
                        <div
                            class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold shrink-0 shadow-inner">
                            💰</div>
                        <div class="min-w-0">
                            <span
                                class="text-[9px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md uppercase">Beli
                                Langsung</span>
                            <h4 class="font-black text-gray-900 text-base truncate">Rp
                                {{ number_format($trade->amount, 0, ',', '.') }}</h4>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="lg:text-right shrink-0">
                    <p class="text-xs text-gray-400 font-medium mb-1">Penjual: <span
                            class="text-gray-700 font-bold">{{ $trade->receiver->name }}</span></p>
                    @include('user.partials.status-badge', ['status' => $trade->status])
                </div>

                <div
                    class="w-full lg:w-auto flex flex-col gap-2 shrink-0 border-t lg:border-t-0 pt-4 lg:pt-0 border-gray-100">
                    @if($trade->status === 'pending')
                    <span
                        class="text-xs font-bold text-gray-400 bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-100 block text-center">⏳
                        Menunggu Respon Penjual...</span>

                    @elseif($trade->status === 'negotiating')
                    <a href="https://wa.me/{{ $trade->receiver->whatsapp_number }}" target="_blank"
                        class="w-full text-center bg-emerald-500 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-emerald-600 transition flex items-center justify-center gap-1.5 shadow-xs">
                        📱 Diskusi via WhatsApp
                    </a>

                    @elseif($trade->status === 'awaiting_payment')
                    @if($trade->payment_method === 'Transfer')
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl max-w-[320px]">
                        <p class="text-xs font-bold text-blue-900 mb-2">💳 Selesaikan Invoice Transfer Escrow</p>
                        <p class="text-[11px] text-blue-700 leading-relaxed mb-3">Silakan transfer pas sejumlah nominal
                            ke Rekening Admin BarterPlace:<br><b class="text-gray-900">Mandiri 141-00123-4567 a/n Rekber
                                BarterPlace</b></p>

                        <form action="{{ route('trade.uploadProof', $trade->id) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-2">
                            @csrf @method('PATCH')
                            <input type="file" name="payment_proof" required accept="image/*"
                                class="w-full text-[10px] bg-white border p-1 rounded-lg">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] py-2 rounded-lg transition">Kirim
                                Bukti Transfer</button>
                        </form>
                    </div>
                    @else
                    <div
                        class="bg-amber-50 border border-amber-100 p-3 rounded-xl text-xs text-amber-800 max-w-[260px]">
                        🤝 <b>Tagihan COD Terbit:</b> Silakan ketemuan langsung di kampus ITS. Siapkan uang pas tunai
                        senilai <b>Rp {{ number_format($trade->amount, 0, ',', '.') }}</b> saat bertemu.
                    </div>
                    <form action="{{ route('trade.complete', $trade->id) }}" method="POST" class="mt-2">
                        @csrf @method('PATCH')
                        <button type="submit"
                            onclick="return confirm('Pastikan Anda sudah memegang barang dan menyerahkan uang tunai!')"
                            class="w-full bg-green-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-green-700 transition">
                            ✅ Konfirmasi Barang Diterima (COD)
                        </button>
                    </form>
                    @endif

                    @elseif($trade->status === 'paid')
                    <div class="bg-green-50 border border-green-100 p-3 rounded-xl text-xs text-green-800 mb-2">
                        🔒 <b>Escrow Aman:</b> Uang Anda sudah berada di sistem BarterPlace.
                    </div>
                    <form action="{{ route('trade.complete', $trade->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                            onclick="return confirm('Konfirmasi bahwa barang sudah Anda terima dengan baik saat COD?')"
                            class="w-full bg-green-600 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-green-700 transition shadow-sm">
                            ✅ Konfirmasi Barang Diterima
                        </button>
                    </form>

                    @elseif($trade->status === 'completed')
                    <span
                        class="text-xs font-bold text-green-700 bg-green-50 px-4 py-2.5 rounded-xl border border-green-100 block text-center">🎉
                        Transaksi Sukses Selesai</span>
                    @endif
                </div>

            </div>
            @empty
            <div class="bg-white border border-gray-100 p-12 rounded-3xl text-center text-gray-400 text-sm">Kamu belum
                mengajukan penawaran ke barang siapa pun.</div>
            @endforelse
        </div>

    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Hilangkan status aktif dari semua tombol
            tabButtons.forEach(btn => {
                btn.classList.remove('border-green-600', 'text-green-600');
                btn.classList.add('border-transparent', 'text-gray-400');
            });

            // Set tombol aktif
            this.classList.remove('border-transparent', 'text-gray-400');
            this.classList.add('border-green-600', 'text-green-600');

            // Sembunyikan semua panel konten
            tabPanels.forEach(panel => panel.classList.add('hidden'));

            // Tampilkan panel target
            const targetSelector = this.getAttribute('data-target');
            document.querySelector(targetSelector).classList.remove('hidden');
        });
    });
});
</script>
@endsection