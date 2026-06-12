@extends('layouts.app')

@section('title', 'Masuk Akun - BarterPlace')

@section('content')
<main class="max-w-md mx-auto p-6 my-12">
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
            <p class="text-gray-400 text-sm mt-1">Masuk untuk mengelola inventory dan tawaran bartermu.</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-xs font-medium">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Email Kampus</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition"
                    placeholder="Masukkan Email ITS Terdaftar">
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Password</label>
                </div>
                <input type="password" name="password" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition"
                    placeholder="Masukkan password kamu">
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 rounded-sm border-gray-300 text-green-600 focus:ring-green-500">
                    <span class="text-xs text-gray-500 font-medium">Ingat Saya di Perangkat Ini</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-bold text-sm py-3.5 rounded-2xl hover:bg-green-700 transition shadow-lg shadow-green-100 cursor-pointer mt-2">
                Masuk ke Akun
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-gray-100 text-xs text-gray-400">
            Belum terdaftar? <a href="{{ route('register') }}" class="text-green-600 font-bold hover:underline">Buat
                akun baru</a>
        </div>
    </div>
</main>
@endsection