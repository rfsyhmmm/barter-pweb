<div
    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition duration-300">
    <div class="w-full h-52 bg-gray-100 flex items-center justify-center relative overflow-hidden">
        <span
            class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm text-gray-700 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-sm z-10">
            {{ $item->category }}
        </span>

        @auth
        @if(auth()->id() !== $item->user_id)
        <a href="{{ route('report.create', $item->id) }}" title="Laporkan Barang Bermasalah"
            class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center bg-white/80 backdrop-blur-sm text-red-500 hover:bg-red-500 hover:text-white rounded-full shadow-sm z-10 transition duration-200 opacity-0 group-hover:opacity-100">
            <span class="text-xs">🚩</span>
        </a>
        @endif
        @endauth

        @if($item->image_path)
        <img src="{{ asset('images/items/' . $item->image_path) }}"
            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
        @else
        <div class="text-gray-400 text-sm font-medium">No Image</div>
        @endif
    </div>

    <div class="p-5 flex-grow flex flex-col">
        @if($showOwner ?? true)
        <div class="mb-1">
            <a href="{{ route('user.show', $item->user->id) }}"
                class="text-xs text-gray-400 font-medium hover:text-green-600 hover:underline transition">
                👤 {{ $item->user->name }}
            </a>
        </div>
        @endif

        <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-green-600 transition truncate"
            title="{{ $item->title }}">
            {{ $item->title }}
        </h3>

        <p class="text-xs font-bold {{ $item->price > 0 ? 'text-green-600' : 'text-blue-600' }} mb-3">
            {{ $item->price > 0 ? 'Rp ' . number_format($item->price, 0, ',', '.') : '🤝 Murni Barter' }}
        </p>

        <p class="text-gray-500 text-xs line-clamp-2 mb-5 flex-grow">{{ $item->description }}</p>

        <div class="flex gap-2 mt-auto">
            @auth
            @if(auth()->id() !== $item->user_id && auth()->user()->role !== 'admin')
            <a href="{{ route('trade.propose', $item->id) }}"
                class="flex-1 bg-black text-white text-center text-xs font-bold py-2.5 rounded-xl hover:bg-gray-800 transition shadow-sm flex items-center justify-center">
                Ajukan Penawaran
            </a>

            <form action="{{ route('cart.store') }}" method="POST" class="m-0 p-0">
                @csrf
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                <button type="submit" title="Tambah ke Keranjang"
                    class="bg-gray-50 hover:bg-green-50 hover:text-green-700 text-gray-600 border border-gray-200 p-2.5 rounded-xl transition flex items-center justify-center h-[38px] w-[38px] cursor-pointer text-sm">
                    🛒
                </button>
            </form>
            @else
            <button disabled
                class="w-full bg-gray-100 text-gray-400 text-xs font-bold py-2.5 rounded-xl cursor-not-allowed">
                {{ auth()->user()->role === 'admin' ? 'Mode Admin' : 'Barangmu Sendiri'}}
            </button>
            @endif
            @else
            <a href="{{ route('login') }}"
                class="w-full bg-gray-100 text-gray-600 hover:text-black text-center text-xs font-bold py-2.5 rounded-xl transition border border-gray-200">
                Masuk untuk Menawar
            </a>
            @endauth
        </div>
    </div>
</div>