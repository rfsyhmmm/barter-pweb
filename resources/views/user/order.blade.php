@extends('layouts.app')

@section('title', 'Daftar Penawaran & Pesanan - BarterPlace')

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">

    @if(empty(auth()->user()->whatsapp_number) || empty(auth()->user()->bank_name) ||
    empty(auth()->user()->bank_account_number))
    <div
        class="bg-red-50 border border-red-200 p-5 rounded-3xl mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="text-red-500 text-3xl mt-1 sm:mt-0">⚠️</div>
            <div>
                <h3 class="text-red-900 font-extrabold text-sm uppercase tracking-wider">Aksi Diperlukan: Profil Belum
                    Lengkap</h3>
                <p class="text-red-700 text-xs mt-1 leading-relaxed">
                    Sistem mendeteksi <b>Nomor WhatsApp</b> atau <b>Data Rekening Pencairan</b> kamu masih kosong.<br>
                    Kamu belum bisa mengajukan maupun menerima penawaran barter sebelum melengkapinya.
                </p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}"
            class="w-full sm:w-auto text-center bg-red-600 text-white text-xs font-bold px-6 py-3 rounded-xl hover:bg-red-700 transition shrink-0">
            Lengkapi Profil
        </a>
    </div>
    @endif
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
                    <p class="text-xs text-gray-400 font-medium mb-1">Oleh: <span
                            class="text-gray-700 font-bold">{{ $trade->sender->name }}</span></p>
                    @include('user.partials.status-badge', ['status' => $trade->status])
                </div>

                <div
                    class="w-full lg:w-auto flex flex-col sm:flex-row gap-2 shrink-0 border-t lg:border-t-0 pt-4 lg:pt-0 border-gray-100">

                    @if($trade->status === 'pending')
                    <div class="w-full sm:max-w-xs flex flex-col sm:flex-row gap-2">
                        <form action="{{ route('trade.negotiate', $trade->id) }}" method="POST" class="w-full">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="w-full bg-green-600 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-green-700 transition cursor-pointer">
                                💬 Lanjut Diskusi WA
                            </button>
                        </form>
                        <form action="{{ route('trade.reject', $trade->id) }}" method="POST" class="w-full">
                            @csrf @method('PATCH')
                            <button type="submit" onclick="return confirm('Tolak penawaran ini?')"
                                class="w-full bg-white border border-gray-200 text-red-600 font-bold text-xs px-5 py-3 rounded-xl hover:bg-red-50 transition cursor-pointer">
                                Tolak
                            </button>
                        </form>
                    </div>

                    @elseif($trade->status === 'negotiating')
                    <div class="w-full sm:max-w-xs flex flex-col gap-2">

                        @php
                        // Normalisasi nomor WA (awalan 0 -> 62)
                        $waNumber = $trade->sender->whatsapp_number;
                        if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                        }

                        // Susun detail tawaran
                        if ($trade->senderItem) {
                        $offerText = "Barter dengan *{$trade->senderItem->title}*";
                        if ($trade->amount > 0) {
                        $offerText .= " + tambahan *Rp " . number_format($trade->amount, 0, ',', '.') . "*";
                        }
                        } else {
                        $offerText = "Beli seharga *Rp " . number_format($trade->amount, 0, ',', '.') . "*";
                        }

                        // Rakit pesan (format profesional, emoji ringan)
                        $authName = auth()->user()->name;
                        $waMessage = "Halo *{$trade->sender->name}*,\n\n"
                        . "Saya *{$authName}* dari *BarterPlace*.\n\n"
                        . "Saya ingin menindaklanjuti tawaranmu berikut:\n\n"
                        . "Barang : *{$trade->receiverItem->title}*\n"
                        . "Tawaran : {$offerText}\n\n"
                        . "Mari kita diskusikan lebih lanjut. Terima kasih!";

                        $waUrl = "https://wa.me/{$waNumber}?text=" . rawurlencode($waMessage);
                        @endphp

                        <a href="{{ $waUrl }}" target="_blank"
                            class="w-full bg-emerald-500 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-emerald-600 transition flex items-center justify-center gap-1.5 shadow-xs">
                            📱 Hubungi Via WA
                        </a>

                        <form action="{{ route('trade.invoice', $trade->id) }}" method="POST"
                            class="w-full flex gap-1 m-0 p-0">
                            @csrf @method('PATCH')
                            @if($trade->amount > 0)
                            <select name="payment_method" required
                                class="bg-gray-50 border border-gray-200 rounded-xl px-2 text-xs font-bold focus:outline-none w-1/2">
                                <option value="Transfer">Transfer</option>
                                <option value="COD">Tunai (COD)</option>
                            </select>
                            <button type="submit"
                                class="w-1/2 bg-black text-white font-bold text-xs px-3 py-3 rounded-xl hover:bg-gray-800 transition cursor-pointer">
                                🤝 Buat Tagihan
                            </button>
                            @else
                            <input type="hidden" name="payment_method" value="COD">
                            <button type="submit"
                                class="w-full bg-black text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-gray-800 transition cursor-pointer">
                                🤝 Deal (COD)
                            </button>
                            @endif
                        </form>
                    </div>

                    @elseif($trade->status === 'awaiting_payment')
                    <div class="w-full sm:max-w-xs text-center">
                        <span
                            class="text-xs font-bold text-gray-400 bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 block w-full">⏳
                            Menunggu Pembayaran</span>
                    </div>

                    @elseif($trade->status === 'paid')
                    <div class="w-full sm:max-w-xs flex flex-col gap-2 text-left">
                        <div
                            class="bg-green-50 border border-green-100 p-3 rounded-xl text-xs text-green-800 w-full font-medium">
                            🔒 <b>Dana Escrow Aman:</b> Pembeli sudah melunasi pembayaran ke Admin. Silakan lakukan
                            ketemuan (COD) untuk serah terima barang!
                        </div>

                        <div class="bg-gray-50 border border-gray-100 p-3 rounded-xl w-full flex flex-col gap-1.5">
                            <div class="flex justify-between text-[11px] text-gray-500 font-medium">
                                <span>Metode Pembayaran:</span>
                                <span class="font-bold text-gray-800">{{ $trade->payment_method }}</span>
                            </div>
                            <div
                                class="flex justify-between text-[11px] text-gray-500 font-medium border-b border-gray-200 pb-1.5 mb-1">
                                <span>Nominal Pencairan:</span>
                                <span class="font-bold text-green-600">Rp
                                    {{ number_format($trade->amount, 0, ',', '.') }}</span>
                            </div>

                            @if($trade->payment_proof)
                            <a href="{{ asset('images/proofs/' . $trade->payment_proof) }}" target="_blank"
                                class="w-full text-center bg-white border border-gray-200 text-gray-700 font-bold text-[11px] py-2 rounded-lg hover:bg-gray-100 hover:text-black transition shadow-xs flex items-center justify-center gap-1">
                                🔍 Lihat Bukti Transfer Pembeli
                            </a>
                            @endif
                        </div>
                    </div>

                    @elseif($trade->status === 'completed')
                    <div class="w-full sm:max-w-xs text-left">
                        <div
                            class="bg-gray-50 border border-gray-100 text-gray-500 p-3 rounded-xl text-xs font-medium w-full">
                            ✅ Selesai. Dana/Barang telah berpindah tangan.
                        </div>
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
                    <p class="text-xs text-gray-400 font-medium mb-1">Penjual: <span
                            class="text-gray-700 font-bold">{{ $trade->receiver->name }}</span></p>
                    @include('user.partials.status-badge', ['status' => $trade->status])
                </div>

                <div
                    class="w-full lg:w-auto flex flex-col gap-2 shrink-0 border-t lg:border-t-0 pt-4 lg:pt-0 border-gray-100">

                    @if($trade->status === 'pending')
                    <div class="w-full sm:max-w-xs flex gap-2 h-[42px]">
                        <div
                            class="flex-1 text-xs font-bold text-gray-400 bg-gray-50 border border-gray-100 rounded-xl flex items-center justify-center">
                            ⏳ Menunggu...
                        </div>

                        <form action="{{ route('trade.cancel', $trade->id) }}" method="POST"
                            class="shrink-0 m-0 p-0 h-full">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin membatalkan penawaran ini?')"
                                class="h-full bg-white border border-gray-200 text-red-600 font-bold text-xs px-4 rounded-xl hover:bg-red-50 hover:border-red-200 transition cursor-pointer shadow-sm flex items-center justify-center">
                                Batal
                            </button>
                        </form>
                    </div>

                    @elseif($trade->status === 'negotiating')
                    <div class="w-full sm:max-w-xs">

                        @php
                        // Normalisasi nomor WA (awalan 0 -> 62)
                        $waNumber = $trade->receiver->whatsapp_number;
                        if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                        }

                        // Susun detail tawaran
                        if ($trade->senderItem) {
                        $offerText = "Barter dengan *{$trade->senderItem->title}*";
                        if ($trade->amount > 0) {
                        $offerText .= " + tambahan *Rp " . number_format($trade->amount, 0, ',', '.') . "*";
                        }
                        } else {
                        $offerText = "Beli seharga *Rp " . number_format($trade->amount, 0, ',', '.') . "*";
                        }

                        // Rakit pesan (format profesional, emoji ringan)
                        $authName = auth()->user()->name;
                        $waMessage = "Halo *{$trade->receiver->name}*,\n\n"
                        . "Saya *{$authName}* dari *BarterPlace*.\n\n"
                        . "Terkait penawaran saya berikut:\n\n"
                        . "Barang : *{$trade->receiverItem->title}*\n"
                        . "Penawaran : {$offerText}\n\n"
                        . "Mari kita diskusikan lebih lanjut. Terima kasih!";
                        $waUrl = "https://wa.me/{$waNumber}?text=" . rawurlencode($waMessage);
                        @endphp

                        <a href="{{ $waUrl }}" target="_blank"
                            class="w-full text-center bg-emerald-500 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-emerald-600 transition flex items-center justify-center gap-1.5 shadow-xs">
                            📱 Diskusi via WhatsApp
                        </a>
                    </div>

                    @elseif($trade->status === 'awaiting_payment')
                    <div class="w-full sm:max-w-xs flex flex-col gap-2">
                        @if($trade->payment_method === 'Transfer')
                        <div class="bg-blue-50 border border-blue-100 p-4 rounded-2xl w-full">
                            <p class="text-xs font-bold text-blue-900 mb-2">💳 Selesaikan Invoice Transfer Escrow</p>
                            <p class="text-[11px] text-blue-700 leading-relaxed mb-3">Silakan transfer pas sejumlah
                                nominal ke Rekening Admin BarterPlace:<br><b class="text-gray-900">Mandiri
                                    141-00123-4567 a/n Rekber BarterPlace</b></p>

                            <form action="{{ route('trade.uploadProof', $trade->id) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-2 w-full m-0 p-0">
                                @csrf @method('PATCH')
                                <input type="file" name="payment_proof" required accept="image/*"
                                    class="w-full text-[10px] bg-white border p-1.5 rounded-lg focus:outline-none">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] py-2.5 rounded-lg transition cursor-pointer">Kirim
                                    Bukti Transfer</button>
                            </form>
                        </div>
                        @else
                        <div class="bg-amber-50 border border-amber-100 p-3 rounded-xl text-xs text-amber-800 w-full">
                            🤝 <b>Tagihan COD Terbit:</b> Silakan ketemuan langsung di kampus ITS. Siapkan uang pas
                            tunai senilai <b>Rp {{ number_format($trade->amount, 0, ',', '.') }}</b> saat bertemu.
                        </div>
                        <form action="{{ route('trade.complete', $trade->id) }}" method="POST" class="w-full m-0 p-0">
                            @csrf @method('PATCH')
                            <button type="submit"
                                onclick="return confirm('Pastikan Anda sudah memegang barang dan menyerahkan uang tunai!')"
                                class="w-full bg-green-600 text-white font-bold text-xs px-4 py-3 rounded-xl hover:bg-green-700 transition shadow-sm cursor-pointer">
                                ✅ Konfirmasi Barang Diterima
                            </button>
                        </form>
                        @endif
                    </div>

                    @elseif($trade->status === 'paid')
                    <div class="w-full sm:max-w-xs flex flex-col gap-2">
                        <div class="bg-green-50 border border-green-100 p-3 rounded-xl text-xs text-green-800 w-full">
                            🔒 <b>Escrow Aman:</b> Uang Anda sudah berada di sistem.
                        </div>
                        <form action="{{ route('trade.complete', $trade->id) }}" method="POST" class="w-full m-0 p-0">
                            @csrf @method('PATCH')
                            <button type="submit"
                                onclick="return confirm('Konfirmasi bahwa barang sudah Anda terima dengan baik saat COD?')"
                                class="w-full bg-green-600 text-white font-bold text-xs px-5 py-3 rounded-xl hover:bg-green-700 transition shadow-sm cursor-pointer">
                                ✅ Konfirmasi Barang Diterima
                            </button>
                        </form>
                    </div>

                    @elseif($trade->status === 'completed')
                    <div class="w-full sm:max-w-xs text-center">
                        <span
                            class="text-xs font-bold text-green-700 bg-green-50 px-4 py-3 rounded-xl border border-green-100 block w-full">🎉
                            Transaksi Selesai</span>
                    </div>
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
            tabButtons.forEach(btn => {
                btn.classList.remove('border-green-600', 'text-green-600');
                btn.classList.add('border-transparent', 'text-gray-400');
            });

            this.classList.remove('border-transparent', 'text-gray-400');
            this.classList.add('border-green-600', 'text-green-600');

            tabPanels.forEach(panel => panel.classList.add('hidden'));

            const targetSelector = this.getAttribute('data-target');
            document.querySelector(targetSelector).classList.remove('hidden');
        });
    });
});
</script>
@endsection