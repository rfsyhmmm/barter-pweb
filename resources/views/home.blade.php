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
        @include('partials.item-card', ['item' => $item, 'showOwner' => true])
        @empty
        <p class="col-span-full text-center text-gray-400 py-8">Belum ada barang tersedia.</p>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $items->appends(request()->query())->links() }}
    </div>
</main>

@endsection