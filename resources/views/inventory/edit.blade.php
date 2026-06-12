@extends('layouts.app')

@section('title', 'Edit Barang - BarterPlace')

@section('content')
<main class="max-w-2xl mx-auto p-6 mb-12 mt-8">
    <div class="mb-6">
        <a href="{{ route('inventory.index') }}"
            class="text-sm text-gray-500 hover:text-gray-900 transition flex items-center gap-2">
            &larr; Batal & Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-amber-50 border-b border-amber-100 p-6">
            <h2 class="text-xl font-bold text-amber-900">✏️ Edit Informasi Barang</h2>
            <p class="text-sm text-amber-700 mt-1">Perbarui detail informasi atau ubah status ketersediaan barang
                milikmu.</p>
        </div>

        <form action="{{ route('inventory.update', $item->id) }}" method="POST" enctype="multipart/form-data"
            class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ $item->title }}" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-amber-500 focus:border-amber-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                        class="text-red-500">*</span></label>
                <select name="category" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-amber-500 focus:border-amber-500 outline-none transition bg-white">
                    <option value="Buku & Alat Tulis" {{ $item->category == 'Buku & Alat Tulis' ? 'selected' : '' }}>
                        Buku & Alat Tulis</option>
                    <option value="Elektronik & Gadget"
                        {{ $item->category == 'Elektronik & Gadget' ? 'selected' : '' }}>Elektronik & Gadget</option>
                    <option value="Fashion & Pakaian" {{ $item->category == 'Fashion & Pakaian' ? 'selected' : '' }}>
                        Fashion & Pakaian</option>
                    <option value="Perlengkapan Kos" {{ $item->category == 'Perlengkapan Kos' ? 'selected' : '' }}>
                        Perlengkapan Kos</option>
                    <option value="Lainnya" {{ $item->category == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Harga Patokan (Rupiah)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-sm">Rp</span>
                    <input type="number" name="price" value="{{ $item->price }}" min="0"
                        class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                </div>
                <p class="text-xs text-gray-400 mt-2">Isi <span class="font-bold">0</span> jika barang ini hanya murni
                    untuk barter tanpa tambahan uang.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Kondisi Barang <span
                        class="text-red-500">*</span></label>
                <textarea name="description" required rows="4"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-amber-500 focus:border-amber-500 outline-none transition">{{ $item->description }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Barang Baru (Opsional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition cursor-pointer">
                <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah foto saat ini.</p>
            </div>

            <button type="submit"
                class="w-full bg-amber-500 text-white font-bold py-3.5 rounded-xl hover:bg-amber-600 transition cursor-pointer shadow-md">
                Simpan Perubahan
            </button>
        </form>
    </div>
</main>
@endsection