@extends('layouts.app')

@section('title', 'Keranjang Saya - BarterPlace')

@section('content')
<main class="max-w-4xl mx-auto p-6 mb-12">
    <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Keranjang Incaran</h2>
        <p class="text-gray-400 text-sm">Simpan dulu barang yang kamu minati, ajukan barter kapan saja.</p>
    </div>

    <div class="space-y-4">
        @forelse($cartItems as $itemCart)
        <div
            class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden shrink-0 border">
                    @if($itemCart->item->image_path)
                    <img src="{{ asset('images/items/' . $itemCart->item->image_path) }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">📦</div>
                    @endif
                </div>
                <div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $itemCart->item->category }}</span>
                    <h3 class="font-bold text-base text-gray-900">{{ $itemCart->item->title }}</h3>
                    <p class="text-xs text-gray-500">Pemilik: {{ $itemCart->item->user->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                @if($itemCart->item->status == 'available')
                <a href="{{ route('trade.propose', $itemCart->item->id) }}"
                    class="flex-1 sm:flex-none text-center bg-black text-white text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-gray-800 transition">
                    🤝 Ajukan Barter
                </a>
                @else
                <button disabled
                    class="flex-1 sm:flex-none bg-gray-100 text-gray-400 text-xs font-bold px-4 py-2.5 rounded-xl cursor-not-allowed">
                    ❌ Tidak Tersedia
                </button>
                @endif

                <form action="{{ route('cart.destroy', $itemCart->id) }}" method="POST" class="shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Hapus barang ini dari keranjang?')"
                        class="bg-white border border-gray-200 text-gray-500 hover:text-red-500 p-2.5 rounded-xl transition cursor-pointer">
                        🗑️
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-gray-400 text-sm bg-white p-12 rounded-2xl border border-dashed border-gray-200 text-center">
            <p>Keranjangmu masih kosong. Jelajahi Market untuk mencari barang menarik!</p>
        </div>
        @endforelse
    </div>
</main>
@endsection