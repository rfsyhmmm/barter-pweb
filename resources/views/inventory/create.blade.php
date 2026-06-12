<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <main class="max-w-2xl mx-auto p-6">
        <a href="{{ route('inventory.index') }}"
            class="text-sm text-gray-400 hover:text-gray-600 mb-6 inline-block">&larr; Kembali ke Inventory</a>

        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold mb-2">Add New Item</h2>
            <p class="text-gray-400 text-sm mb-8">Masukkan detail barang yang ingin kamu tawarkan.</p>

            @if ($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Item Name</label>
                        <input type="text" name="title" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500"
                            placeholder="Contoh: Kamera Analog Nikon">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Category</label>
                        <select name="category" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500">
                            <option value="Electronics">Electronics</option>
                            <option value="Musical">Musical</option>
                            <option value="Home & Garden">Home & Garden</option>
                            <option value="Hobby">Hobby</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Description</label>
                        <textarea name="description" rows="4" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-green-500"
                            placeholder="Ceritakan kondisi barang kamu..."></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Item Photo</label>
                        <input type="file" name="image"
                            class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200">
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 text-white font-bold py-4 rounded-2xl hover:bg-green-700 transition shadow-lg shadow-green-100">
                        Save to Inventory
                    </button>
                </form>
        </div>
    </main>

</body>

</html>