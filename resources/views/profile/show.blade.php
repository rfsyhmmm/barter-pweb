@extends('layouts.app')

@section('title', $user->name . ' - Profil BarterPlace')

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">

    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-12">
        <div class="h-32 bg-gradient-to-r from-green-500 to-emerald-600"></div>

        <div class="px-8 pb-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 -mt-16 sm:-mt-12 relative z-10 mb-6">
                <div
                    class="w-32 h-32 rounded-full border-4 border-white bg-gray-100 flex items-center justify-center overflow-hidden shadow-md shrink-0">
                    @if($user->profile_picture)
                    <img src="{{ asset('images/profiles/' . $user->profile_picture) }}"
                        class="w-full h-full object-cover">
                    @else
                    <span class="text-4xl font-bold text-gray-400 uppercase">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>

                <div class="text-center sm:text-left flex-1">
                    <h1
                        class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center justify-center sm:justify-start gap-2">
                        {{ $user->name }}
                        <span
                            class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full uppercase tracking-wider font-bold">Terverifikasi</span>
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">Bergabung sejak {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 rounded-2xl p-4 border border-gray-100">
                <div class="text-center">
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Barang Tersedia</p>
                    <p class="text-2xl font-black text-gray-900">{{ $items->count() }}</p>
                </div>
                <div class="text-center border-l border-gray-200">
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-wider">Barter Sukses</p>
                    <p class="text-2xl font-black text-green-600">{{ $successfulTrades }}</p>
                </div>
                <div
                    class="text-center border-l border-gray-200 col-span-2 md:col-span-2 flex items-center justify-center">
                    @if($user->whatsapp_number)
                    <span
                        class="text-xs font-medium text-emerald-700 bg-emerald-100 px-4 py-2 rounded-xl flex items-center gap-2">
                        <span class="text-lg">📱</span> Nomor WA Terhubung (Aman)
                    </span>
                    @else
                    <span
                        class="text-xs font-medium text-red-700 bg-red-100 px-4 py-2 rounded-xl flex items-center gap-2">
                        <span class="text-lg">⚠️</span> Belum melengkapi nomor kontak
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8 border-b border-gray-200 pb-4">
        <h2 class="text-2xl font-bold text-gray-900">Etalase Barang</h2>
        <p class="text-gray-500 text-sm mt-1">Daftar barang milik {{ $user->name }} yang siap ditukar atau dibeli.</p>
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

                @if($item->image_path)
                <img src="{{ asset('images/items/' . $item->image_path) }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="text-gray-400 text-sm font-medium">No Image</div>
                @endif
            </div>

            <div class="p-5 flex-grow flex flex-col">
                <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-green-600 transition truncate">
                    {{ $item->title }}</h3>
                <p class="text-xs font-bold {{ $item->price > 0 ? 'text-green-600' : 'text-blue-600' }} mb-3">
                    {{ $item->price > 0 ? 'Rp ' . number_format($item->price, 0, ',', '.') : '🤝 Murni Barter' }}
                </p>
                <p class="text-gray-500 text-xs line-clamp-2 mb-5 flex-grow">{{ $item->description }}</p>

                <div class="flex gap-2 mt-auto">
                    @auth
                    @if(auth()->id() !== $user->id)
                    <a href="{{ route('trade.propose', $item->id) }}"
                        class="w-full bg-black text-white text-center text-xs font-bold py-2.5 rounded-xl hover:bg-gray-800 transition shadow-xs">
                        Ajukan Penawaran
                    </a>
                    @else
                    <button disabled
                        class="w-full bg-gray-100 text-gray-400 text-xs font-bold py-2.5 rounded-xl cursor-not-allowed">
                        Barangmu Sendiri
                    </button>
                    @endif
                    @else
                    <a href="{{ route('login') }}"
                        class="w-full bg-gray-100 text-gray-600 hover:text-black text-center text-xs font-bold py-2.5 rounded-xl transition border border-gray-200">
                        Masuk untuk Menawar
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-3xl border border-dashed border-gray-200">
            <span class="text-4xl block mb-2">📦</span>
            <h3 class="font-bold text-gray-900">Etalase Kosong</h3>
            <p class="text-gray-400 text-sm">Pengguna ini belum mempublikasikan barang apapun ke Market.</p>
        </div>
        @endforelse
    </div>

</main>
@endsection