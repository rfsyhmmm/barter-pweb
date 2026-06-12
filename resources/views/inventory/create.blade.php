@extends('layouts.app')

@section('title', 'Tambah Barang - BarterPlace')

@section('content')
<main class="max-w-2xl mx-auto p-6 mb-12 mt-8">
    <div class="mb-6">
        <a href="{{ route('inventory.index') }}"
            class="text-sm text-gray-500 hover:text-gray-900 transition flex items-center gap-2">
            &larr; Kembali ke Inventory
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-green-50 border-b border-green-100 p-6">
            <h2 class="text-xl font-bold text-green-900">📦 Tambah Barang Baru</h2>
            <p class="text-sm text-green-700 mt-1">Masukkan detail barang yang ingin kamu tawarkan di BarterPlace.</p>
        </div>

        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data"
            class="p-6 sm:p-8 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang <span
                        class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder="Contoh: Buku Pemrograman Laravel 11"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-green-500 focus:border-green-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                        class="text-red-500">*</span></label>
                <select name="category" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-green-500 focus:border-green-500 outline-none transition bg-white">
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Buku & Alat Tulis">Buku & Alat Tulis</option>
                    <option value="Elektronik & Gadget">Elektronik & Gadget</option>
                    <option value="Fashion & Pakaian">Fashion & Pakaian</option>
                    <option value="Perlengkapan Kos">Perlengkapan Kos</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Harga Patokan (Rupiah)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-sm">Rp</span>
                    <input type="number" name="price" value="0" min="0" placeholder="0"
                        class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-green-500 focus:border-green-500 outline-none transition">
                </div>
                <p class="text-xs text-gray-400 mt-2">Isi <span class="font-bold">0</span> jika barang ini hanya murni
                    untuk barter tanpa tambahan opsi pembelian/uang.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Kondisi Barang <span
                        class="text-red-500">*</span></label>
                <textarea name="description" required rows="4"
                    placeholder="Jelaskan kondisi barang secara jujur (misal: minus pemakaian, kelengkapan kotak, dll)..."
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-green-500 focus:border-green-500 outline-none transition"></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Foto Barang <span
                        class="text-red-500">*</span></label>
                <input type="file" name="image" required accept="image/*"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition cursor-pointer">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 transition cursor-pointer shadow-md">
                Pasang Barang
            </button>
        </form>
    </div>
</main>
@endsection