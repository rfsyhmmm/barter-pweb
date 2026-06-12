@extends('layouts.app')

@section('title', 'Tambah Barang Baru - BarterPlace')

@section('content')
<main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Barang</h1>
        <p class="text-gray-500 text-sm mt-1">Unggah barang yang ingin kamu barter atau jual ke ekosistem ITS.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Barang <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}"
                    placeholder="Contoh: Buku Pemrograman Laravel"
                    class="w-full border border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl p-3 outline-none transition">

                @error('title')
                <p class="text-red-500 text-xs mt-1.5 font-medium">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                        class="text-red-500">*</span></label>
                <select name="category"
                    class="w-full border border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl p-3 outline-none transition bg-white cursor-pointer">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Buku & Alat Tulis" {{ old('category') == 'Buku & Alat Tulis' ? 'selected' : '' }}>
                        Buku & Alat Tulis</option>
                    <option value="Elektronik & Gadget"
                        {{ old('category') == 'Elektronik & Gadget' ? 'selected' : '' }}>Elektronik & Gadget</option>
                    <option value="Pakaian & Fashion" {{ old('category') == 'Pakaian & Fashion' ? 'selected' : '' }}>
                        Pakaian & Fashion</option>
                    <option value="Perlengkapan Kost" {{ old('category') == 'Perlengkapan Kost' ? 'selected' : '' }}>
                        Perlengkapan Kost</option>
                    <option value="Otomotif & Aksesoris"
                        {{ old('category') == 'Otomotif & Aksesoris' ? 'selected' : '' }}>Otomotif & Aksesoris</option>
                    <option value="Olahraga & Alat Outdoor"
                        {{ old('category') == 'Olahraga & Alat Outdoor' ? 'selected' : '' }}>Olahraga & Alat Outdoor
                    </option>
                    <option value="Hobi & Alat Musik" {{ old('category') == 'Hobi & Alat Musik' ? 'selected' : '' }}>
                        Hobi & Alat Musik</option>
                    <option value="Peralatan Dapur & Rumah Tangga"
                        {{ old('category') == 'Peralatan Dapur & Rumah Tangga' ? 'selected' : '' }}>Peralatan Dapur &
                        Rumah Tangga</option>
                    <option value="Kesehatan & Perawatan Tubuh"
                        {{ old('category') == 'Kesehatan & Perawatan Tubuh' ? 'selected' : '' }}>Kesehatan & Perawatan
                        Tubuh</option>
                    <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>

                @error('category')
                <p class="text-red-500 text-xs mt-1.5 font-medium">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi <span
                        class="text-red-500">*</span></label>
                <textarea name="description" rows="4"
                    placeholder="Jelaskan kondisi barang, minus (jika ada), dan alasan ditukar/dijual..."
                    class="w-full border border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl p-3 outline-none transition">{{ old('description') }}</textarea>

                @error('description')
                <p class="text-red-500 text-xs mt-1.5 font-medium">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Harga Patokan (Opsional)</label>
                <p class="text-[11px] text-gray-400 mb-2 leading-relaxed">Isi dengan nilai rupiah barang ini jika kamu
                    berniat menjualnya atau untuk patokan saat Tukar Tambah.</p>

                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-sm">Rp</span>
                    <input type="number" name="price" value="{{ old('price', 0) }}" min="0" max="1000000000"
                        class="w-full border border-gray-200 focus:border-green-500 focus:ring-green-500 rounded-xl pl-12 pr-4 py-3 outline-none transition">
                </div>

                @error('price')
                <p class="text-red-500 text-xs mt-1.5 font-medium">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Barang</label>
                <input type="file" name="image" accept="image/png, image/jpeg, image/jpg"
                    class="w-full border border-gray-200 rounded-xl p-2 bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 transition cursor-pointer">

                @error('image')
                <p class="text-red-500 text-xs mt-1.5 font-medium">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <a href="{{ route('inventory.index') }}"
                    class="w-1/3 text-center bg-gray-50 border border-gray-200 text-gray-600 font-bold px-6 py-3 rounded-xl hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                    class="w-2/3 bg-green-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-700 transition shadow-sm cursor-pointer">
                    Simpan Barang
                </button>
            </div>

        </form>
    </div>
</main>
@endsection