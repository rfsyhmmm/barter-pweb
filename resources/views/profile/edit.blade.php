@extends('layouts.app')

@section('title', 'Pengaturan Profil - BarterPlace')

@section('content')
<main class="max-w-2xl mx-auto p-6 my-12">
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-xs">
        <div class="mb-6 border-b border-gray-100 pb-4">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Pengaturan Profil</h2>
            <p class="text-gray-400 text-xs mt-0.5">Kelola informasi data diri, foto profil, dan kontak nomor WhatsApp
                aktif.</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-6 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                <div
                    class="w-16 h-16 bg-gray-200 rounded-full overflow-hidden shrink-0 border border-gray-200 flex items-center justify-center">
                    @if($user->profile_picture)
                    <img src="{{ asset('images/profiles/' . $user->profile_picture) }}"
                        class="w-full h-full object-cover">
                    @else
                    <span class="text-sm font-bold text-gray-500 uppercase">{{ substr($user->name, 0, 2) }}</span>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Foto
                        Profil</label>
                    <input type="file" name="profile_picture"
                        class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 file:cursor-pointer hover:file:bg-green-100">
                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Nama Pengguna</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Nomor
                    WhatsApp</label>
                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-green-500 transition"
                    placeholder="Contoh: 081234567890">
                <p class="text-[10px] text-amber-600 font-semibold mt-1.5">
                    ⚠️ Penting: Nomor WhatsApp wajib diisi agar kamu bisa mengajukan penawaran barter ke pengguna lain.
                </p>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-green-600 text-white font-bold text-sm py-3 rounded-xl hover:bg-green-700 transition shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>
@endsection