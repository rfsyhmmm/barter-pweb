@extends('layouts.app')

@section('title', 'Daftar Akun - BarterPlace')

@section('content')
<main class="max-w-md mx-auto p-6 my-12">
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Mulai Barter di Kampus</h2>
            <p class="text-gray-400 text-sm mt-1">Gabung komunitas BarterPlace khusus mahasiswa ITS.</p>
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

        <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition"
                    placeholder="Contoh: John Doe">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Email Institusi
                    (ITS)</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition"
                    placeholder="masukkan Email ITS">
                <p class="text-[10px] text-amber-600 font-semibold mt-1.5 flex items-center gap-1">
                    ⚠️ Wajib menggunakan email ITS (@student.its.ac.id atau @its.ac.id)
                </p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Password</label>
                <input type="password" name="password" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition"
                    placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Konfirmasi
                    Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition"
                    placeholder="Ulangi password kamu">
            </div>

            <button type="submit"
                class="w-full bg-green-600 text-white font-bold text-sm py-3.5 rounded-2xl hover:bg-green-700 transition shadow-lg shadow-green-100 cursor-pointer mt-2">
                Daftar Sekarang
            </button>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-gray-100 text-xs text-gray-400">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-green-600 font-bold hover:underline">Masuk di
                sini</a>
        </div>
    </div>
</main>
@endsection