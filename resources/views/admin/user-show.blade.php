@extends('layouts.app')

@section('title', 'Detail Pengguna - BarterPlace')

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 mb-12">

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}"
            class="text-sm font-medium text-gray-500 hover:text-gray-900 transition flex items-center gap-2">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="p-6 sm:p-8 flex flex-col md:flex-row gap-8 items-start md:items-center justify-between">

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 w-full md:w-auto">
                <div
                    class="w-20 h-20 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-3xl font-bold uppercase shrink-0 shadow-inner">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">{{ $user->name }}</h2>
                        <span
                            class="text-[10px] px-3 py-1.5 rounded-full font-bold uppercase tracking-wider
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->status }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-1.5 flex items-center gap-2"><span>✉️</span> {{ $user->email }}
                    </p>
                    <p class="text-sm text-gray-600 flex items-center gap-2"><span>📱</span>
                        {{ $user->whatsapp_number ?? 'Belum mengatur WhatsApp' }}</p>
                    <p class="text-xs text-gray-400 mt-4 font-medium uppercase tracking-wider">Bergabung sejak
                        {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <div
                class="w-full md:w-auto bg-gray-50 p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col gap-3">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider text-center md:text-left">
                    Tindakan & Navigasi
                </p>

                <a href="{{ route('user.show', $user->id) }}" target="_blank"
                    class="w-full px-6 py-3 rounded-xl font-bold text-sm transition shadow-sm bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 hover:text-black text-center flex items-center justify-center gap-2">
                    <span>👁️</span> Lihat Profil Publik
                </a>

                <form action="{{ route('admin.user.toggleBan', $user->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" onclick="return confirm('Yakin ingin mengubah status akun ini?')"
                        class="w-full px-6 py-3 rounded-xl font-bold text-sm transition shadow-sm
                        {{ $user->status === 'active' ? 'bg-red-600 text-white hover:bg-red-700 hover:shadow-md' : 'bg-green-600 text-white hover:bg-green-700 hover:shadow-md' }}">
                        {{ $user->status === 'active' ? '🚨 Blokir Pengguna (Ban)' : '✅ Pulihkan Akun (Unban)' }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</main>
@endsection