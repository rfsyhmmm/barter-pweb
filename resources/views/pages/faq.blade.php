@extends('layouts.app')

@section('title', 'Pusat Bantuan & FAQ - BarterPlace')

@section('content')
<main class="max-w-4xl mx-auto px-6 py-12 mb-20">

    <div class="text-center mb-16">
        <span
            class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">Pusat
            Bantuan</span>
        <h1 class="text-4xl font-extrabold text-gray-900 mt-4 mb-4">Pertanyaan Seputar BarterPlace</h1>
        <p class="text-gray-500 max-w-xl mx-auto">Temukan panduan lengkap tentang bagaimana cara melakukan barter, tukar
            tambah, dan pembayaran aman di lingkungan kampus ITS.</p>
    </div>

    <div class="space-y-6">

        <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                <span class="text-2xl">🤔</span> Apa itu BarterPlace?
            </h3>
            <p class="text-gray-500 text-sm leading-relaxed ml-8">
                BarterPlace adalah marketplace eksklusif untuk mahasiswa ITS. Di sini kamu tidak hanya bisa membeli
                barang dengan uang, tapi juga bisa menukarnya dengan barang tidak terpakai milikmu (Barter), atau
                menggabungkan barangmu dengan tambahan sedikit uang (Tukar Tambah).
            </p>
        </div>

        <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                <span class="text-2xl">🤝</span> Bagaimana sistem Barter / Tukar Tambah bekerja?
            </h3>
            <p class="text-gray-500 text-sm leading-relaxed ml-8">
                Pilih barang yang kamu inginkan di halaman utama, klik <b>Ajukan Penawaran</b>. Pilih salah satu barang
                dari Inventory kamu yang ingin kamu korbankan. Jika nilai barangmu lebih rendah dari barang target, kamu
                bisa mengisi nominal "Tambahan Uang" untuk meyakinkan si penjual.
            </p>
        </div>

        <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                <span class="text-2xl">💳</span> Apa itu "Beli Langsung" (Manual Escrow)?
            </h3>
            <p class="text-gray-500 text-sm leading-relaxed ml-8">
                Jika kamu tidak punya barang untuk ditukar, kamu bisa membeli menggunakan metode <b>Transfer</b>
                (Escrow). Kamu akan mentransfer uang ke Rekening Admin BarterPlace terlebih dahulu. Uang baru akan
                diteruskan ke penjual SETELAH kamu mengonfirmasi bahwa barang sudah kamu terima dengan baik.
            </p>
        </div>

        <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                <span class="text-2xl">📍</span> Di mana saya bisa melakukan transaksi COD?
            </h3>
            <p class="text-gray-500 text-sm leading-relaxed ml-8">
                Sistem kami dirancang khusus untuk mahasiswa ITS. Kami merekomendasikan COD (Cash on Delivery /
                Ketemuan) di area publik kampus yang ramai dan aman, seperti <b>Kantin Pusat, Perpustakaan ITS, atau
                    Taman Alumni</b>.
            </p>
        </div>

        <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                <span class="text-2xl">🚩</span> Bagaimana jika pengguna lain melakukan penipuan?
            </h3>
            <p class="text-gray-500 text-sm leading-relaxed ml-8">
                Gunakan tombol <b>"Lapor (Report)"</b> yang ada di setiap postingan barang. Tim Moderator (Admin) kami
                akan meninjau laporan tersebut dan memiliki hak penuh untuk memblokir akun (Banned) serta men-takedown
                barang yang bermasalah.
            </p>
        </div>

    </div>

    <div class="mt-16 bg-blue-50 border border-blue-100 p-8 rounded-3xl text-center">
        <h3 class="text-xl font-bold text-blue-900 mb-2">Masih punya pertanyaan?</h3>
        <p class="text-blue-700 text-sm mb-6">Jangan ragu untuk menghubungi tim dukungan mahasiswa kami.</p>
        <a href="mailto:support@barterplace.its.ac.id"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-8 py-3 rounded-xl transition shadow-sm">
            ✉️ Hubungi Dukungan
        </a>
    </div>

</main>
@endsection