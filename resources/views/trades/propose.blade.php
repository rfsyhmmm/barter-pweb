@extends('layouts.app')

@section('title', 'Propose a Trade')

@section('content')
<main class="max-w-5xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-center min-h-[60vh]">

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative">
        <span class="absolute top-2 left-2 bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full font-medium">Their
            Item</span>

        <div
            class="w-full h-64 bg-gray-200 rounded-xl mb-4 flex items-center justify-center text-gray-400 overflow-hidden">
            @if($targetItem->image_path)
            <img src="{{ asset('images/' . $targetItem->image_path) }}" class="w-full h-full object-cover">
            @else
            <span>[No Image]</span>
            @endif
        </div>

        <h2 class="text-xl font-bold">{{ $targetItem->title }}</h2>
        <p class="text-sm text-gray-500 mb-4">Oleh: {{ $targetItem->user->name }} &bull; 0.8 miles away</p>
        <div class="bg-gray-50 p-3 rounded-xl text-sm italic text-gray-600">
            "{{ $targetItem->description }}"
        </div>
    </div>

    <div id="your-offer-slot"
        class="border-2 border-dashed border-gray-300 rounded-2xl p-8 flex flex-col items-center justify-center text-center min-h-[350px] bg-white relative">
        <span class="absolute top-2 right-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Your
            Offer</span>

        <div id="empty-state" class="flex flex-col items-center">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 mb-3">📦
            </div>
            <h3 class="font-semibold text-gray-700">Select an Item</h3>
            <p class="text-xs text-gray-400 max-w-xs mt-1">Choose an item from your inventory below to offer in
                exchange for the espresso machine.</p>
        </div>

        <div id="filled-state" class="hidden w-full flex flex-col items-center">
            <div id="offer-image-placeholder"
                class="w-full h-48 bg-gray-100 rounded-xl mb-3 flex items-center justify-center text-gray-400">
            </div>
            <h3 id="offer-title" class="font-bold text-lg text-gray-800"></h3>
            <p id="offer-category" class="text-xs text-gray-400"></p>
        </div>
    </div>

</main>

<section class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg p-4 rounded-t-3xl">
    <div class="max-w-5xl mx-auto">
        <h3 class="text-sm font-bold text-gray-500 mb-3">My Inventory</h3>

        <div class="flex gap-4 overflow-x-auto pb-2">
            @foreach($myInventory as $item)
            <div class="inventory-card min-w-[140px] bg-gray-50 p-3 rounded-xl border border-gray-200 cursor-pointer hover:border-green-500 transition"
                data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-category="{{ $item->category }}"
                data-image="{{ asset('images/' . $item->image_path) }}">

                <div
                    class="w-full h-24 bg-gray-200 rounded-lg mb-2 flex items-center justify-center text-xs text-gray-400 overflow-hidden">
                    @if($item->image_path)
                    <img src="{{ asset('images/' . $item->image_path) }}" class="w-full h-full object-cover">
                    @else
                    <span>[No Image]</span>
                    @endif
                </div>
                <h4 class="font-semibold text-xs truncate">{{ $item->title }}</h4>
                <p class="text-[10px] text-gray-400">{{ $item->category }}</p>
            </div>
            @endforeach
            <a href="{{ route('inventory.create') }}"
                class="min-w-[140px] border border-dashed border-gray-300 p-3 rounded-xl flex flex-col items-center justify-center cursor-pointer text-gray-400 hover:bg-gray-50 hover:text-green-600 transition">
                <span class="text-xl">+</span>
                <span class="text-[10px] mt-1 font-semibold">Add New Item</span>
            </a>
        </div>

        <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
            <div>
                <h4 class="text-xs font-bold">Ready to propose?</h4>
                <p class="text-[10px] text-gray-400">Sarah usually responds within 24hrs.</p>
            </div>

            <form action="{{ route('trade.store') }}" method="POST" id="form-trade">
                @csrf
                <input type="hidden" name="sender_item_id" id="input-sender-item">
                <input type="hidden" name="receiver_item_id" value="{{ $targetItem->id }}">
                <input type="hidden" name="receiver_id" value="{{ $targetItem->user_id }}">

                <button id="btn-submit" type="submit" disabled
                    class="bg-gray-300 text-gray-500 font-semibold text-sm px-6 py-2 rounded-full cursor-not-allowed transition">
                    Select an item
                </button>
            </form>

        </div>
    </div>
</section>

<script>
const cards = document.querySelectorAll('.inventory-card');

cards.forEach(card => {
    card.addEventListener('click', function() {
        // Bersihkan warna hijau dari kartu lain
        cards.forEach(c => c.classList.remove('border-green-500', 'bg-green-50/50'));

        // Beri warna hijau pada kartu yang diklik
        this.classList.add('border-green-500', 'bg-green-50/50');

        // Ambil data
        const itemId = this.getAttribute('data-id');
        const itemTitle = this.getAttribute('data-title');
        const itemCategory = this.getAttribute('data-category');
        const itemImage = this.getAttribute('data-image');

        // Sembunyikan state kosong
        document.getElementById('empty-state').classList.add('hidden');

        // Munculkan state terisi dan ganti kontennya
        const filledState = document.getElementById('filled-state');
        filledState.classList.remove('hidden');

        document.getElementById('offer-title').innerText = itemTitle;
        document.getElementById('offer-category').innerText = itemCategory;
        document.getElementById('offer-image-placeholder').innerHTML =
            `<img src="${itemImage}" class="w-full h-full object-cover">`;

        // Nyalakan tombol
        const btnSubmit = document.getElementById('btn-submit');
        btnSubmit.disabled = false;
        btnSubmit.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        btnSubmit.classList.add('bg-green-600', 'text-white', 'hover:bg-green-700', 'cursor-pointer');
        btnSubmit.innerText = 'Propose Trade';

        // Set input tersembunyi
        document.getElementById('input-sender-item').value = itemId;
    });
});
</script>

@endsection