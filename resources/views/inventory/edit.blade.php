@extends('layouts.app')

@section('title', 'Edit Item - BarterPlace')

@section('content')
<main class="max-w-2xl mx-auto p-6 mb-12">
    <a href="{{ route('inventory.index') }}" class="text-sm text-gray-400 hover:text-gray-600 mb-6 inline-block">&larr;
        Kembali ke Inventory</a>

    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold mb-2">Edit Item Detail</h2>
        <p class="text-gray-400 text-sm mb-8">Perbarui informasi barang yang ingin kamu barterkan.</p>

        @if ($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            <strong>Ups, ada yang salah:</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('inventory.update', $item->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT') <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Item Name</label>
                <input type="text" name="title" value="{{ old('title', $item->title) }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500"
                    placeholder="Contoh: Kamera Analog Nikon">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Category</label>
                <select name="category" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500">
                    <option value="Electronics" {{ $item->category == 'Electronics' ? 'selected' : '' }}>Electronics
                    </option>
                    <option value="Musical" {{ $item->category == 'Musical' ? 'selected' : '' }}>Musical</option>
                    <option value="Home & Garden" {{ $item->category == 'Home & Garden' ? 'selected' : '' }}>Home &
                        Garden</option>
                    <option value="Hobby" {{ $item->category == 'Hobby' ? 'selected' : '' }}>Hobby</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500"
                    placeholder="Ceritakan kondisi barang kamu...">{{ old('description', $item->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Item Photo</label>

                @if($item->image_path)
                <div class="mb-3 flex items-center gap-4 p-3 border border-gray-100 rounded-xl bg-gray-50">
                    <img src="{{ asset('images/' . $item->image_path) }}" class="w-16 h-16 object-cover rounded-lg">
                    <span class="text-xs text-gray-500">Foto saat ini. Biarkan kosong jika tidak ingin mengubah
                        foto.</span>
                </div>
                @endif

                <input type="file" name="image"
                    class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit"
                    class="flex-1 bg-green-600 text-white font-bold py-4 rounded-2xl hover:bg-green-700 transition shadow-lg shadow-green-100">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</main>
@endsection