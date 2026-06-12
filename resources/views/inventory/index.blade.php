@extends('layouts.app')

@section('title', 'My Inventory - Dashboard')

@section('content')

<main class="max-w-5xl mx-auto p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold">My Inventory</h2>
            <p class="text-gray-400 text-sm">Kelola barang-barang yang ingin kamu barterkan.</p>
        </div>
        <a href="{{ route('inventory.create') }}"
            class="bg-black text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-800 transition">
            + Add New Item
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full">
            <div
                class="w-full h-48 bg-gray-100 rounded-xl mb-4 flex items-center justify-center overflow-hidden relative">
                <span class="absolute top-2 left-2 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md shadow-sm z-10
                    {{ $item->status == 'available' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $item->status == 'draft' ? 'bg-gray-200 text-gray-600' : '' }}
                    {{ $item->status == 'traded' ? 'bg-blue-100 text-blue-700' : '' }}">
                    {{ $item->status == 'available' ? 'Di Market' : '' }}
                    {{ $item->status == 'draft' ? 'Draft' : '' }}
                    {{ $item->status == 'traded' ? 'Sudah Laku' : '' }}
                </span>

                @if($item->image_path)
                <img src="{{ asset('images/' . $item->image_path) }}"
                    class="w-full h-full object-cover {{ $item->status == 'traded' ? 'grayscale opacity-70' : '' }}">
                @else
                <span class="text-gray-400 text-xs">No Image</span>
                @endif
            </div>

            <div class="flex-grow">
                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $item->category }}</span>
                <h3 class="font-bold text-lg mb-1">{{ $item->title }}</h3>
                <p class="text-gray-500 text-xs line-clamp-2">{{ $item->description }}</p>
            </div>

            @if($item->status == 'draft')
            <div class="mt-4">
                <form action="{{ route('inventory.publish', $item->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full bg-black text-white text-xs font-bold py-2.5 rounded-xl hover:bg-gray-800 transition cursor-pointer shadow-sm">
                        🚀 Ajukan ke Market
                    </button>
                </form>
            </div>
            @endif

            @if($item->status != 'traded')
            <div class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center">
                <a href="{{ route('inventory.edit', $item->id) }}"
                    class="text-xs font-bold text-blue-600 hover:underline">Edit Detail</a>

                <form action="{{ route('inventory.destroy', $item->id) }}" method="POST"
                    onsubmit="return confirm('Hapus barang ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-xs font-bold text-red-500 hover:underline cursor-pointer">Delete</button>
                </form>
            </div>
            @else
            <div class="mt-4 pt-3 border-t border-gray-50 text-center">
                <span class="text-xs font-bold text-blue-500 flex items-center justify-center gap-1">
                    🎉 Barang telah dibarter
                </span>
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-200">
            <p class="text-gray-400">Belum ada barang di inventory kamu.</p>
        </div>
        @endforelse
    </div>
</main>

@endsection