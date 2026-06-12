@extends('layouts.app')

@section('title', 'Ajukan Penawaran - BarterPlace')

@section('content')
<form action="{{ route('trade.store') }}" method="POST" id="form-trade" class="pb-40">
    @csrf
    <input type="hidden" name="sender_item_id" id="input-sender-item">
    <input type="hidden" name="receiver_item_id" value="{{ $targetItem->id }}">
    <input type="hidden" name="receiver_id" value="{{ $targetItem->user_id }}">

    <div id="targetItemData" data-price="{{ $targetItem->price ?? 0 }}" class="hidden"></div>

    <main class="max-w-5xl mx-auto p-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-start min-h-[60vh] mt-4">

        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 relative">
            <span
                class="absolute top-4 left-4 bg-amber-100 text-amber-800 text-[10px] uppercase tracking-wider px-3 py-1 rounded-full font-bold z-10">Target
                Barang</span>

            <div
                class="w-full h-72 bg-gray-100 rounded-2xl mb-5 flex items-center justify-center text-gray-400 overflow-hidden relative">
                @if($targetItem->image_path)
                <img src="{{ asset('images/items/' . $targetItem->image_path) }}" class="w-full h-full object-cover">
                @else
                <span>🚫 [Tanpa Gambar]</span>
                @endif
            </div>

            <h2 class="text-2xl font-extrabold text-gray-900 mb-1">{{ $targetItem->title }}</h2>

            <p class="text-sm font-bold {{ $targetItem->price > 0 ? 'text-green-600' : 'text-blue-600' }} mb-3">
                {{ $targetItem->price > 0 ? 'Harga Patokan: Rp ' . number_format($targetItem->price, 0, ',', '.') : '🤝 Murni Barter' }}
            </p>

            <p class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                <span
                    class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">{{ substr($targetItem->user->name, 0, 1) }}</span>
                Oleh: {{ $targetItem->user->name }}
            </p>

            <div class="bg-gray-50 p-4 rounded-xl text-sm italic text-gray-600 border border-gray-100">
                "{{ $targetItem->description }}"
            </div>
        </div>

        <div class="flex flex-col gap-6">

            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm">

                <div
                    class="mb-6 p-4 bg-blue-50/50 rounded-2xl border border-blue-100 flex justify-between items-center">
                    <div>
                        <p class="font-bold text-gray-900 text-sm">Beli Langsung (Full Pay)</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">Matikan untuk menukar dengan barang.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="fullPayToggle" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider"
                        id="amountLabel">Tambahan Uang (Opsional)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-500 text-sm">Rp</span>
                        <input type="number" name="amount" id="amountInput" value="0" min="0"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3.5 text-sm font-bold focus:ring-green-500 focus:border-green-500 outline-none transition">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2" id="amountHelper">Isi nominal jika ingin melakukan <span
                            class="font-bold text-gray-600">Tukar Tambah (Partial Pay)</span>. Biarkan 0 jika murni
                        tukar barang.</p>
                </div>
            </div>

            <div id="your-offer-slot"
                class="border-2 border-dashed border-gray-300 rounded-3xl p-8 flex flex-col items-center justify-center text-center flex-1 bg-white relative transition-all">
                <span
                    class="absolute top-4 right-4 bg-green-100 text-green-800 text-[10px] uppercase tracking-wider px-3 py-1 rounded-full font-bold z-10">Barang
                    Tawaranmu</span>

                <div id="empty-state" class="flex flex-col items-center">
                    <div
                        class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-2xl mb-4 border border-gray-100 shadow-inner">
                        📦</div>
                    <h3 class="font-bold text-gray-700">Pilih Barang Tawaran</h3>
                    <p class="text-xs text-gray-400 max-w-xs mt-1.5 leading-relaxed">Pilih salah satu barang dari
                        inventory kamu di bawah untuk ditukarkan.</p>
                </div>

                <div id="filled-state" class="hidden w-full flex flex-col items-center">
                    <div id="offer-image-placeholder"
                        class="w-full h-40 bg-gray-100 rounded-2xl mb-4 flex items-center justify-center text-gray-400 overflow-hidden shadow-sm">
                    </div>
                    <h3 id="offer-title" class="font-extrabold text-lg text-gray-900 mb-1"></h3>
                    <p id="offer-category"
                        class="text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-md uppercase font-bold tracking-wider">
                    </p>
                </div>

                <div id="cash-state" class="hidden w-full flex flex-col items-center justify-center h-full">
                    <div
                        class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-4xl mb-4 shadow-inner">
                        💳</div>
                    <h3 class="font-extrabold text-xl text-blue-900">Pembayaran Tunai</h3>
                    <p class="text-xs text-blue-600 max-w-xs mt-2 font-medium">Kamu memilih untuk membeli barang ini
                        tanpa menukarkan barang milikmu.</p>
                </div>
            </div>

        </div>
    </main>

    <section id="inventory-sheet"
        class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] p-5 rounded-t-[2rem] transition-all duration-300 z-50">
        <div class="max-w-5xl mx-auto">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Pilih dari Inventory Kamu</h3>

            <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar">
                @forelse($myInventory as $item)
                <div class="inventory-card min-w-[150px] max-w-[150px] bg-gray-50 p-3 rounded-2xl border border-gray-200 cursor-pointer hover:border-green-500 hover:shadow-md transition duration-200"
                    data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-category="{{ $item->category }}"
                    data-image="{{ $item->image_path ? asset('images/items/' . $item->image_path) : '' }}">

                    <div
                        class="w-full h-24 bg-white rounded-xl mb-3 flex items-center justify-center text-xs text-gray-400 overflow-hidden border border-gray-100 shadow-sm">
                        @if($item->image_path)
                        <img src="{{ asset('images/items/' . $item->image_path) }}" class="w-full h-full object-cover">
                        @else
                        <span>🚫</span>
                        @endif
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm truncate mb-1">{{ $item->title }}</h4>
                    <p class="text-[10px] text-gray-500 font-bold uppercase truncate">{{ $item->category }}</p>
                </div>
                @empty
                <div
                    class="text-sm text-gray-500 italic py-8 px-4 text-center w-full bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    Kamu belum punya barang di inventory.
                </div>
                @endforelse

                <a href="{{ route('inventory.create') }}"
                    class="min-w-[150px] border-2 border-dashed border-gray-300 bg-gray-50 p-3 rounded-2xl flex flex-col items-center justify-center cursor-pointer text-gray-500 hover:bg-green-50 hover:border-green-500 hover:text-green-700 transition">
                    <span class="text-2xl mb-1">⊕</span>
                    <span class="text-xs font-bold">Tambah Barang</span>
                </a>
            </div>

            <div
                class="mt-2 pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-center sm:text-left">
                    <h4 class="text-sm font-bold text-gray-900">Siap mengajukan penawaran?</h4>
                    <p class="text-xs text-gray-500 mt-0.5">Penjual akan meninjau tawaranmu dan membalas via WA.</p>
                </div>

                <button id="btn-submit" type="submit" disabled
                    class="w-full sm:w-auto bg-gray-300 text-gray-500 font-bold text-sm px-8 py-3.5 rounded-xl cursor-not-allowed transition shadow-sm">
                    Pilih barang dulu
                </button>
            </div>
        </div>
    </section>
</form>

<style>
.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.inventory-card');
    const toggleFullPay = document.getElementById('fullPayToggle');
    const amountLabel = document.getElementById('amountLabel');
    const amountInput = document.getElementById('amountInput');
    const amountHelper = document.getElementById('amountHelper');

    const slotBox = document.getElementById('your-offer-slot');
    const stateEmpty = document.getElementById('empty-state');
    const stateFilled = document.getElementById('filled-state');
    const stateCash = document.getElementById('cash-state');

    const inventorySheet = document.getElementById('inventory-sheet');
    const btnSubmit = document.getElementById('btn-submit');
    const inputSenderItem = document.getElementById('input-sender-item');

    // Ambil nilai harga dengan aman dari div HTML yang disembunyikan di atas
    const targetItemData = document.getElementById('targetItemData');
    const targetPrice = parseInt(targetItemData.getAttribute('data-price')) || 0;

    let selectedItemId = null;

    cards.forEach(card => {
        card.addEventListener('click', function() {
            if (toggleFullPay.checked) return;

            cards.forEach(c => {
                c.classList.remove('border-green-500', 'bg-green-50/50', 'ring-2',
                    'ring-green-500', 'ring-offset-2');
                c.classList.add('border-gray-200');
            });

            this.classList.remove('border-gray-200');
            this.classList.add('border-green-500', 'bg-green-50/50', 'ring-2', 'ring-green-500',
                'ring-offset-2');

            selectedItemId = this.getAttribute('data-id');
            const itemTitle = this.getAttribute('data-title');
            const itemCategory = this.getAttribute('data-category');
            const itemImage = this.getAttribute('data-image');

            stateEmpty.classList.add('hidden');
            stateCash.classList.add('hidden');
            stateFilled.classList.remove('hidden');

            document.getElementById('offer-title').innerText = itemTitle;
            document.getElementById('offer-category').innerText = itemCategory;

            if (itemImage) {
                document.getElementById('offer-image-placeholder').innerHTML =
                    `<img src="${itemImage}" class="w-full h-full object-cover">`;
            } else {
                document.getElementById('offer-image-placeholder').innerHTML =
                    `<span class="text-3xl">🚫</span>`;
            }

            inputSenderItem.value = selectedItemId;
            enableSubmitBtn("🤝 Ajukan Barter", "bg-green-600", "hover:bg-green-700");
        });
    });

    toggleFullPay.addEventListener('change', function() {
        if (this.checked) {
            amountLabel.innerText = "Nominal Pembayaran (Wajib)";
            amountHelper.innerHTML =
                "Uang tidak akan dipotong langsung. Transaksi menggunakan sistem <span class='font-bold text-gray-700'>Manual Escrow (Bayar Nanti)</span>.";

            // Set input otomatis sesuai targetPrice
            if (targetPrice > 0) amountInput.value = targetPrice;
            amountInput.setAttribute('required', 'required');

            inputSenderItem.value = "";
            cards.forEach(c => c.classList.remove('border-green-500', 'bg-green-50/50', 'ring-2',
                'ring-green-500', 'ring-offset-2'));

            stateEmpty.classList.add('hidden');
            stateFilled.classList.add('hidden');
            stateCash.classList.remove('hidden');

            slotBox.classList.remove('border-dashed', 'border-gray-300');
            slotBox.classList.add('border-solid', 'border-blue-200', 'bg-blue-50/30');

            inventorySheet.style.opacity = '0.5';
            inventorySheet.style.pointerEvents = 'none';

            enableSubmitBtn("💳 Ajukan Pembelian", "bg-blue-600", "hover:bg-blue-700");

        } else {
            amountLabel.innerText = "Tambahan Uang (Opsional)";
            amountHelper.innerHTML =
                "Isi nominal jika ingin melakukan <span class='font-bold text-gray-600'>Tukar Tambah (Partial Pay)</span>. Biarkan 0 jika murni tukar barang.";
            amountInput.value = "0";
            amountInput.removeAttribute('required');

            stateCash.classList.add('hidden');
            slotBox.classList.remove('border-solid', 'border-blue-200', 'bg-blue-50/30');
            slotBox.classList.add('border-dashed', 'border-gray-300');

            inventorySheet.style.opacity = '1';
            inventorySheet.style.pointerEvents = 'auto';

            if (selectedItemId) {
                stateFilled.classList.remove('hidden');
                inputSenderItem.value = selectedItemId;
                document.querySelector(`.inventory-card[data-id="${selectedItemId}"]`).classList.add(
                    'border-green-500', 'bg-green-50/50', 'ring-2');
                enableSubmitBtn("🤝 Ajukan Barter", "bg-green-600", "hover:bg-green-700");
            } else {
                stateEmpty.classList.remove('hidden');
                disableSubmitBtn();
            }
        }
    });

    function enableSubmitBtn(text, bgColorClass, hoverClass) {
        btnSubmit.disabled = false;
        btnSubmit.className =
            `w-full sm:w-auto text-white font-bold text-sm px-8 py-3.5 rounded-xl transition shadow-md cursor-pointer ${bgColorClass} ${hoverClass}`;
        btnSubmit.innerText = text;
    }

    function disableSubmitBtn() {
        btnSubmit.disabled = true;
        btnSubmit.className =
            "w-full sm:w-auto bg-gray-300 text-gray-500 font-bold text-sm px-8 py-3.5 rounded-xl cursor-not-allowed transition shadow-sm";
        btnSubmit.innerText = "Pilih barang dulu";
    }
});
</script>
@endsection