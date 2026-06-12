@extends('layouts.app')

@section('title', 'BarterPlace - Marketplace')

@section('content')

<header class="max-w-5xl mx-auto px-6 pt-12 pb-6">
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-3xl p-8 border border-green-100/50">
        <h1 class="text-3xl font-extrabold tracking-tight md:text-4xl mb-2 text-gray-900">
            Tukar Barang, Tukar Tambah, <br class="hidden md:inline">atau Beli Langsung.
        </h1>
        <p class="text-gray-500 text-sm max-w-md">
            Marketplace hybrid khusus mahasiswa ITS. Ajukan penawaran barter murni, tambah saldo, atau bayar penuh
            secara aman dengan sistem Escrow!
        </p>
    </div>
</header>

<main class="max-w-5xl mx-auto p-6 mb-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="text-xl font-bold flex items-center gap-2 shrink-0">
            > Available for Trade
            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full font-normal">
                {{ $items->total() }} Items </span>
        </h2>

        <form action="{{ route('home') }}" method="GET" class="w-full md:w-1/2 relative">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                placeholder="Cari nama barang, pengguna, atau kategori..."
                class="w-full bg-white border border-gray-200 rounded-xl pl-4 pr-12 py-2.5 text-sm focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition shadow-sm">
            <button type="submit"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 cursor-pointer">
                🔍
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($items as $item)
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition duration-300">

            <div class="w-full h-52 bg-gray-100 flex items-center justify-center relative overflow-hidden">
                <span
                    class="absolute top-3 left-3 bg-white/90 backdrop-blur-xs text-gray-700 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-xs z-10">
                    {{ $item->category }}
                </span>

                @auth
                @if($item->user_id !== auth()->id())
                <a href="{{ route('report.create', $item->id) }}"
                    class="absolute top-3 right-3 bg-red-50/90 text-red-600 hover:bg-red-600 hover:text-white backdrop-blur-xs text-[10px] font-bold px-2 py-1 rounded-full shadow-xs z-10 transition cursor-pointer"
                    title="Laporkan Barang Ini">
                    🚩 Lapor
                </a>
                @endif
                @endauth

                @if($item->image_path)
                <img src="{{ asset('images/items/' . $item->image_path) }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="text-gray-400 text-sm font-medium">No Image</div>
                @endif
            </div>

            <div class="p-5 flex-grow flex flex-col">
                <div class="flex items-center gap-2 mb-3">
                    <div
                        class="w-6 h-6 bg-green-100 text-green-700 rounded-full flex items-center justify-center text-[10px] font-bold">
                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                    </div>
                    <span class="text-xs text-gray-500 font-medium">{{ $item->user->name }}</span>
                </div>

                <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-green-600 transition truncate">
                    {{ $item->title }}
                </h3>

                <p class="text-xs font-bold {{ $item->price > 0 ? 'text-green-600' : 'text-blue-600' }} mb-3">
                    {{ $item->price > 0 ? 'Rp ' . number_format($item->price, 0, ',', '.') : '🤝 Murni Barter' }}
                </p>

                <p class="text-gray-500 text-xs line-clamp-2 mb-5 flex-grow">
                    {{ $item->description }}
                </p>

                <div class="flex gap-2 mt-auto">
                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <button disabled
                        class="w-full bg-gray-100 text-gray-400 text-xs font-bold py-2.5 rounded-xl cursor-not-allowed border border-gray-200">
                        🔒 Mode Admin: Hanya Pantau
                    </button>
                    @else
                    <a href="{{ route('trade.propose', $item->id) }}"
                        class="flex-1 bg-black text-white flex items-center justify-center text-xs font-bold py-2.5 rounded-xl hover:bg-gray-800 transition shadow-xs">
                        Ajukan Penawaran
                    </a>

                    <form action="{{ route('cart.store') }}" method="POST" class="flex-none m-0 p-0">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <button type="submit"
                            class="bg-white border border-gray-200 text-gray-600 hover:text-green-600 hover:border-green-300 h-full px-3.5 flex items-center justify-center rounded-xl transition cursor-pointer shadow-xs"
                            title="Simpan ke Keranjang">
                            🛒
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-xs">
            <div class="text-3xl mb-2">...</div>
            <p class="text-gray-400 text-sm font-medium">
                {{ !empty($search) ? "Barang dengan kata kunci '{$search}' tidak ditemukan." : "Belum ada barang tersedia saat ini." }}
            </p>
            @if(!empty($search))
            <a href="{{ route('home') }}"
                class="text-green-600 text-xs font-bold hover:underline mt-2 inline-block">Reset Pencarian</a>
            @endif
        </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $items->appends(request()->query())->links() }}
    </div>
</main>

@endsection