@extends('layouts.app')

@section('title', 'Detail Moderasi - BarterPlace')

@section('content')
<main class="max-w-4xl mx-auto p-6 mb-12">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:underline">&larr; Kembali</a>
        <h2 class="text-2xl font-bold text-gray-900">Detail Log Barang</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="md:col-span-1 bg-gray-100 rounded-2xl h-64 flex items-center justify-center overflow-hidden border">
            @if($item->image_path)
            <img src="{{ asset('images/items/' . $item->image_path) }}" class="w-full h-full object-cover">
            @else
            <div class="text-center text-gray-400">
                <span class="text-3xl block">🚫</span>
                <span class="text-xs font-semibold">Tidak Ada Media / Telah Di-takedown</span>
            </div>
            @endif
        </div>

        <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-gray-200 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span
                        class="text-xs bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full font-bold uppercase">{{ $item->category }}</span>
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full uppercase
                        {{ $item->status == 'available' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $item->status == 'banned' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $item->status == 'traded' ? 'bg-blue-100 text-blue-700' : '' }}">
                        Status: {{ $item->status }}
                    </span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $item->title }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ $item->description }}</p>
                <hr class="border-gray-100 mb-4">
                <p class="text-xs text-gray-500"><b>Pemilik asli:</b> {{ $item->user->name }} (ID: {{ $item->user_id }})
                </p>
                <p class="text-xs text-gray-500"><b>Terdaftar pada:</b> {{ $item->created_at->format('d M Y H:i') }}</p>
            </div>

            @if($item->status == 'available')
            <div class="mt-6">
                <form action="{{ route('admin.item.takedown', $item->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        onclick="return confirm('Takedown barang ini? Gambar fisik akan dihapus permanen.')"
                        class="w-full bg-red-600 text-white text-xs font-bold py-2.5 rounded-xl hover:bg-red-700 transition cursor-pointer text-center">
                        🚨 Eksekusi Takedown (Melanggar Aturan)
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <h3 class="font-bold text-lg mb-4 border-b border-gray-100 pb-2">📜 Log Aktivitas Barter Terkait</h3>
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 border-b text-gray-600 font-semibold">
                <tr>
                    <th class="px-4 py-3">ID Transaksi</th>
                    <th class="px-4 py-3">Pihak Pengaju (Bidder)</th>
                    <th class="px-4 py-3">Pihak Penerima (Owner)</th>
                    <th class="px-4 py-3">Status Log</th>
                    <th class="px-4 py-3 text-right">Tanggal Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tradeHistory as $trade)
                <tr>
                    <td class="px-4 py-3 font-mono">#{{ $trade->id }}</td>
                    <td class="px-4 py-3">{{ $trade->sender->name }}</td>
                    <td class="px-4 py-3">{{ $trade->receiver->name }}</td>
                    <td class="px-4 py-3">
                        <span class="font-bold uppercase tracking-wider text-[9px]">{{ $trade->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-400">{{ $trade->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Belum ada rekam jejak transaksi
                        untuk barang ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection