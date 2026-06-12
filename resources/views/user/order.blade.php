@extends('layouts.app')
@section('title', 'My Orders - BarterPlace')
@section('content')
<main class="max-w-5xl mx-auto p-6 mb-12">
    <div class="mb-8">
        <h2 class="text-2xl font-bold">My Orders</h2>
        <p class="text-gray-400 text-sm">Kelola penawaran aktif dan riwayat transaksimu.</p>
    </div>

    <h3 class="font-bold text-lg border-b border-gray-200 pb-2 mb-4">⏳ Tawaran Aktif</h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="space-y-4">
            <h4 class="font-semibold text-gray-600 text-sm">📥 Masuk ({{ $activeIncoming->count() }})</h4>
            @forelse($activeIncoming as $trade)
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-sm text-gray-600 mb-3"><b>{{ $trade->sender->name }}</b> menawar
                    <b>{{ $trade->receiverItem->title }}</b> milikmu dengan <b>{{ $trade->senderItem->title }}</b>.
                </p>
                <div class="flex gap-2">
                    <form action="{{ route('orders.accept', $trade->id) }}" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <button
                            class="w-full bg-green-600 text-white text-xs font-bold py-2.5 rounded-lg cursor-pointer">Accept</button>
                    </form>
                    <form action="{{ route('orders.reject', $trade->id) }}" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <button
                            class="w-full bg-gray-100 text-gray-600 border border-gray-200 text-xs font-bold py-2.5 rounded-lg cursor-pointer">Reject</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-xs text-gray-400 italic">Belum ada tawaran masuk.</p>
            @endforelse
        </div>

        <div class="space-y-4">
            <h4 class="font-semibold text-gray-600 text-sm">📤 Keluar ({{ $activeOutgoing->count() }})</h4>
            @forelse($activeOutgoing as $trade)
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-sm text-gray-600 mb-3">Kamu menawar <b>{{ $trade->receiverItem->title }}</b> milik
                    <b>{{ $trade->receiver->name }}</b> dengan <b>{{ $trade->senderItem->title }}</b>.
                </p>
                <form action="{{ route('orders.cancel', $trade->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button
                        class="w-full bg-red-50 text-red-600 border border-red-200 text-xs font-bold py-2.5 rounded-lg cursor-pointer">Cancel
                        Order</button>
                </form>
            </div>
            @empty
            <p class="text-xs text-gray-400 italic">Belum ada tawaran keluar.</p>
            @endforelse
        </div>
    </div>

    <h3 class="font-bold text-lg text-green-700 border-b border-gray-200 pb-2 mb-4">✅ Tawaran Diterima</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
        @forelse($acceptedTrades as $trade)
        @php
        $isSender = $trade->sender_id == auth()->id();
        $partner = $isSender ? $trade->receiver : $trade->sender;

        $myItem = $isSender ? $trade->senderItem : $trade->receiverItem;
        $partnerItem = $isSender ? $trade->receiverItem : $trade->senderItem;

        $waNumber = $partner->whatsapp_number;
        if($waNumber && str_starts_with($waNumber, '0')) $waNumber = '62' . substr($waNumber, 1);
        $msg = urlencode("Halo {$partner->name}! Kita deal barter *{$myItem->title}* (milikku) dengan
        *{$partnerItem->title}* (milikmu). Kapan COD?");
        @endphp
        <div class="bg-green-50 p-4 rounded-xl border border-green-200 shadow-sm flex flex-col justify-between">

            <div class="flex items-center justify-between mb-4 bg-white p-3 rounded-lg border border-green-100">
                <div class="flex items-center gap-3 w-[45%]">
                    <div class="w-10 h-10 bg-gray-200 rounded-md overflow-hidden shrink-0">
                        @if($myItem->image_path) <img src="{{ asset('images/' . $myItem->image_path) }}"
                            class="w-full h-full object-cover"> @else <div
                            class="w-full h-full flex items-center justify-center text-xs">📦</div> @endif
                    </div>
                    <div class="truncate">
                        <h4 class="font-bold text-xs text-gray-900 truncate">{{ $myItem->title }}</h4>
                        <p class="text-[9px] text-green-600 font-bold mt-0.5 truncate">Barangmu</p>
                    </div>
                </div>
                <div class="text-green-400 text-sm font-bold shrink-0 px-1">⇄</div>
                <div class="flex items-center gap-3 w-[45%] justify-end text-right">
                    <div class="truncate">
                        <h4 class="font-bold text-xs text-gray-900 truncate">{{ $partnerItem->title }}</h4>
                        <p class="text-[9px] text-gray-500 font-semibold mt-0.5 truncate">{{ $partner->name }}</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-200 rounded-md overflow-hidden shrink-0">
                        @if($partnerItem->image_path) <img src="{{ asset('images/' . $partnerItem->image_path) }}"
                            class="w-full h-full object-cover"> @else <div
                            class="w-full h-full flex items-center justify-center text-xs">📦</div> @endif
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                @if($waNumber)
                <a href="https://wa.me/{{ $waNumber }}?text={{ $msg }}" target="_blank"
                    class="flex-1 flex items-center justify-center bg-green-600 text-white text-xs font-bold py-2.5 rounded-lg hover:bg-green-700 cursor-pointer">💬
                    Chat WA</a>
                @else
                <span
                    class="flex-1 flex items-center justify-center bg-gray-200 text-gray-500 text-xs font-bold py-2.5 rounded-lg">No
                    WA</span>
                @endif

                <form action="{{ route('orders.cancelDeal', $trade->id) }}" method="POST" class="flex-none">
                    @csrf @method('PATCH')
                    <button type="submit" onclick="return confirm('Batal COD dan kembalikan kedua barang ke market?')"
                        class="bg-white border border-red-200 text-red-500 hover:bg-red-50 text-xs font-bold px-4 py-2.5 rounded-lg cursor-pointer transition">Batal
                        Deal</button>
                </form>
            </div>

        </div>
        @empty
        <p class="text-xs text-gray-400 italic col-span-full">Belum ada transaksi berhasil.</p>
        @endforelse
    </div>

    <div class="flex justify-between items-end border-b border-gray-200 pb-2 mb-4">
        <h3 class="font-bold text-lg text-red-700">❌ Tawaran Ditolak</h3>
        @if($rejectedTrades->count() > 0)
        <form action="{{ route('orders.clearRejected') }}" method="POST">
            @csrf @method('DELETE')
            <button type="submit" onclick="return confirm('Hapus semua riwayat ditolak?')"
                class="text-xs font-bold text-red-500 hover:underline cursor-pointer">🗑️ Bersihkan Semua</button>
        </form>
        @endif
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($rejectedTrades as $trade)
        <div class="bg-gray-50 p-3 rounded-xl border border-gray-200">
            <p class="text-xs text-gray-500">Barter <b>{{ $trade->senderItem->title }}</b> &
                <b>{{ $trade->receiverItem->title }}</b> batal.
            </p>
        </div>
        @empty
        <p class="text-xs text-gray-400 italic col-span-full">Riwayat bersih.</p>
        @endforelse
    </div>
</main>
@endsection