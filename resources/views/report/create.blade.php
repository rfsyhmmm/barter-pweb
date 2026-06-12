@extends('layouts.app')
@section('title', 'Laporkan Barang - BarterPlace')
@section('content')
<main class="max-w-xl mx-auto p-6 mb-12">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:underline">&larr; Batal</a>
        <h2 class="text-2xl font-bold text-gray-900">Laporkan Barang</h2>
    </div>

    <div class="bg-red-50 border border-red-100 p-4 rounded-xl mb-6">
        <p class="text-xs text-red-600 font-medium">Kamu sedang melaporkan barang <b>{{ $item->title }}</b> milik
            <b>{{ $item->user->name }}</b>. Tim Admin akan meninjau laporan ini secara manual.</p>
    </div>

    <form action="{{ route('report.store', $item->id) }}" method="POST"
        class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pelaporan <span
                    class="text-red-500">*</span></label>
            <select name="reason" required
                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                <option value="">-- Pilih alasan --</option>
                <option value="Penipuan / Barang Fiktif">Penipuan / Barang Fiktif</option>
                <option value="Barang Berbahaya / Ilegal">Barang Berbahaya / Ilegal (Senjata, Narkoba, dll)</option>
                <option value="Gambar Tidak Pantas / Vulgar">Gambar Tidak Pantas / Vulgar</option>
                <option value="Spam / Duplikat">Spam / Duplikat</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Detail Kronologi / Keterangan <span
                    class="text-red-500">*</span></label>
            <textarea name="description" rows="4" required
                placeholder="Jelaskan secara singkat kenapa barang ini mencurigakan..."
                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 resize-none"></textarea>
            <p class="text-[10px] text-gray-400 mt-1">Maksimal 500 karakter.</p>
        </div>

        <button type="submit"
            class="w-full bg-red-600 text-white font-bold py-3 rounded-xl hover:bg-red-700 transition cursor-pointer">
            Kirim Laporan
        </button>
    </form>
</main>
@endsection