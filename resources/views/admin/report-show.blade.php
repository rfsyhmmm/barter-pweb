@extends('layouts.app')

@section('title', 'Detail Laporan - BarterPlace')

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('admin.dashboard') }}"
            class="text-sm font-medium text-gray-500 hover:text-gray-900 transition flex items-center gap-2">
            &larr; Kembali ke Dashboard
        </a>
        <span
            class="text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-lg bg-gray-100 text-gray-600 border border-gray-200 shadow-sm">
            Report ID: #{{ $report->id }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-red-50 rounded-2xl border border-red-100 p-6 sm:p-8 flex flex-col h-full">
            <div class="flex items-center gap-3 mb-6 border-b border-red-100 pb-4">
                <span class="text-2xl">🚨</span>
                <h2 class="text-xl font-extrabold text-red-900 tracking-tight">Rincian Laporan</h2>
            </div>

            <div class="mb-6">
                <p class="text-xs text-red-600 font-bold uppercase tracking-widest mb-2">Kategori Pelanggaran</p>
                <p class="text-lg font-bold text-red-800 bg-red-100/50 inline-block px-4 py-2 rounded-lg">
                    {{ $report->reason }}</p>
            </div>

            <div class="mb-8 bg-white p-5 rounded-xl border border-red-100 shadow-sm">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Penjelasan Pelapor</p>
                <p class="text-sm text-gray-700 italic leading-relaxed">"{{ $report->description }}"</p>
            </div>

            <div class="mt-auto bg-white/60 p-4 rounded-xl text-sm border border-red-100/50">
                <p class="text-gray-600 mb-2"><b>Pelapor:</b> <a
                        href="{{ route('admin.user.show', $report->reporter->id) }}"
                        class="text-blue-600 font-medium hover:underline">{{ $report->reporter->name }}</a></p>
                <p class="text-gray-600"><b>Waktu Laporan:</b> {{ $report->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8 shadow-sm flex flex-col h-full">
            <h3 class="text-lg font-extrabold text-gray-900 mb-6 border-b border-gray-100 pb-4 tracking-tight">Target
                yang Dilaporkan</h3>

            @if($report->item)
            <div class="flex flex-col sm:flex-row gap-6 items-start mb-8">
                <div
                    class="w-full sm:w-32 h-40 sm:h-32 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-200 shadow-sm">
                    @if($report->item->image_path)
                    <img src="{{ asset('images/' . $report->item->image_path) }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-3xl">🚫</div>
                    @endif
                </div>
                <div class="flex-1 w-full">
                    <h4 class="font-bold text-gray-900 text-xl mb-1">{{ $report->item->title }}</h4>
                    <span
                        class="inline-block text-[10px] font-bold text-gray-600 bg-gray-100 px-2.5 py-1 rounded-md uppercase tracking-wider mb-3">{{ $report->item->category }}</span>

                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-sm">
                        <p class="text-gray-600 mb-1 flex justify-between"><span>Status:</span> <span
                                class="font-bold text-gray-900">{{ strtoupper($report->item->status) }}</span></p>
                        <p class="text-gray-600 flex justify-between"><span>Pemilik:</span> <a
                                href="{{ route('admin.user.show', $report->reportedUser->id) }}"
                                class="text-blue-600 font-medium hover:underline truncate ml-2">{{ $report->reportedUser->name }}</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-auto bg-gray-50 p-5 sm:p-6 rounded-xl border border-gray-200 shadow-inner">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-4 text-center">Eksekusi Cepat</p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.item.show', $report->item->id) }}"
                        class="w-full sm:w-1/2 text-center bg-white border border-gray-300 text-gray-700 text-sm font-bold py-3 rounded-xl hover:bg-gray-100 transition shadow-sm">
                        Cek Barang
                    </a>

                    @if($report->item->status === 'available')
                    <form action="{{ route('admin.item.takedown', $report->item->id) }}" method="POST"
                        class="w-full sm:w-1/2">
                        @csrf @method('PATCH')
                        <button type="submit" onclick="return confirm('Takedown barang ini sekarang?')"
                            class="w-full bg-red-600 text-white text-sm font-bold py-3 rounded-xl hover:bg-red-700 transition cursor-pointer shadow-sm">
                            🚨 Takedown
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @else
            <div
                class="text-center flex-1 flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <span class="text-5xl block mb-4">🗑️</span>
                <p class="text-gray-500 font-medium text-lg">Barang telah ditakedown.</p>
            </div>
            @endif

        </div>
    </div>
</main>
@endsection