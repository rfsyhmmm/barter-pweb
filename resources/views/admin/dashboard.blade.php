@extends('layouts.app')

@section('title', 'Admin Dashboard - BarterPlace')

@section('content')
<main class="max-w-5xl mx-auto p-6 mb-12">
    <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Admin Dashboard</h2>
        <p class="text-gray-400 text-sm">Pantau aktivitas dan statistik ekosistem BarterPlace.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl shadow-sm">
            <h3 class="text-blue-500 text-sm font-bold uppercase tracking-wider mb-2">Total Pengguna</h3>
            <p class="text-3xl font-extrabold text-blue-900">{{ $totalUsers }}</p>
        </div>

        <div class="bg-green-50 border border-green-100 p-6 rounded-2xl shadow-sm">
            <h3 class="text-green-500 text-sm font-bold uppercase tracking-wider mb-2">Total Barang</h3>
            <p class="text-3xl font-extrabold text-green-900">{{ $totalItems }}</p>
        </div>

        <div class="bg-purple-50 border border-purple-100 p-6 rounded-2xl shadow-sm">
            <h3 class="text-purple-500 text-sm font-bold uppercase tracking-wider mb-2">Barter Berhasil</h3>
            <p class="text-3xl font-extrabold text-purple-900">{{ $successfulTrades }}</p>
        </div>
    </div>

    <h3 class="font-bold text-lg mb-4 border-b border-gray-100 pb-2">📦 Listing Barang Terbaru</h3>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-12">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">
                <tr>
                    <th class="px-6 py-4 font-semibold">Barang</th>
                    <th class="px-6 py-4 font-semibold">Kategori</th>
                    <th class="px-6 py-4 font-semibold">Pemilik</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentItems as $item)
                <tr onclick="window.location.href=`{{ route('admin.item.show', $item->id) }}`;"
                    class="hover:bg-gray-100 transition cursor-pointer group">
                    <td class="px-6 py-4 font-medium text-gray-900 group-hover:text-blue-600">{{ $item->title }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $item->category }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $item->user->name }}</td>
                    <td class="px-6 py-4">
                        <span class="text-[10px] px-2 py-1 rounded-full font-bold uppercase
                            {{ $item->status == 'available' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $item->status == 'draft' ? 'bg-gray-200 text-gray-600' : '' }}
                            {{ $item->status == 'traded' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $item->status == 'banned' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            @if($item->status == 'available')
                            <form action="{{ route('admin.item.takedown', $item->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    onclick="event.stopPropagation(); return confirm('Takedown barang ini dari publik?')"
                                    class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg font-bold hover:bg-red-100 transition cursor-pointer">
                                    Takedown
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada barang di database.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h3 class="font-bold text-lg mb-4 border-b border-gray-100 pb-2">👥 Manajemen Pengguna</h3>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-12">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nama Pengguna</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">WhatsApp</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($usersList as $user)
                <tr onclick="window.location.href=`{{ route('admin.user.show', $user->id) }}`;"
                    class="hover:bg-gray-100 transition cursor-pointer group">
                    <td class="px-6 py-4 font-medium text-gray-900 group-hover:text-blue-600">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->whatsapp_number ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span
                            class="text-[10px] px-2.5 py-1 rounded-full font-bold uppercase
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end">
                            <form action="{{ route('admin.user.toggleBan', $user->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="event.stopPropagation();"
                                    class="text-xs font-bold px-3 py-1.5 rounded-lg transition cursor-pointer
                                    {{ $user->status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                    {{ $user->status === 'active' ? 'Blokir (Ban)' : 'Pulihkan (Unban)' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada pengguna terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h3 class="font-bold text-lg mb-4 border-b border-gray-100 pb-2">🚨 Laporan Masuk</h3>
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-12">
        <table class="w-full text-left text-sm">
            <thead class="bg-red-50 border-b border-gray-200 text-red-700">
                <tr>
                    <th class="px-6 py-4 font-semibold">Pelapor</th>
                    <th class="px-6 py-4 font-semibold">Barang Dilaporkan</th>
                    <th class="px-6 py-4 font-semibold">Alasan</th>
                    <th class="px-6 py-4 font-semibold text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reportsList as $report)
                <tr onclick="window.location.href=`{{ route('admin.report.show', $report->id) }}`;"
                    class="hover:bg-red-50 transition cursor-pointer group">
                    <td class="px-6 py-4 font-medium text-gray-900 group-hover:text-blue-600">
                        {{ $report->reporter?->name ?? 'Pengguna Dihapus' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $report->item->title ?? 'Barang Dihapus' }}</td>
                    <td class="px-6 py-4 text-gray-500 font-bold">{{ $report->reason }}</td>
                    <td class="px-6 py-4 text-right">
                        <span
                            class="text-[10px] px-2.5 py-1 rounded-full font-bold uppercase
                            {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $report->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada laporan masuk. Lingkungan
                        aman!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection