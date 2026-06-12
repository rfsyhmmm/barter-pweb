<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * READ: Menampilkan semua barang di dashboard user
     */
    public function index()
    {
        $myUserId = Auth::id();
        $items = Item::where('user_id', $myUserId)->get();
        return view('inventory.index', compact('items'));
    }

    /**
     * CREATE: Menampilkan form tambah barang
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * STORE: Menyimpan barang baru ke database
     */
    public function store(Request $request)
    {
        $myUserId = Auth::id();

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0', // Validasi harga
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:8192'
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Item::create([
            'user_id' => $myUserId,
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price ?? 0, // Simpan harga, default 0
            'image_path' => $imageName,
            'status' => 'draft'
        ]);

        return redirect()->route('inventory.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        //
    }

    /**
     * EDIT: Menampilkan form edit barang
     */
    public function edit(string $id)
    {
        $myUserId = Auth::id();

        // Cari barang berdasarkan ID barang DAN ID pemiliknya (keamanan tambahan)
        $item = Item::where('user_id', $myUserId)->findOrFail($id);
        
        return view('inventory.edit', compact('item'));
    }

    /**
     * UPDATE: Menyimpan perubahan barang
     */
    public function update(Request $request, string $id)
    {
        $myUserId = Auth::id();
        $item = Item::where('user_id', $myUserId)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0', // Validasi harga ditambahkan
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $item->image_path = $imageName; 
        }

        $item->title = $request->title;
        $item->category = $request->category;
        $item->description = $request->description;
        $item->price = $request->price ?? 0; // Update harga
        
        $item->save();

        return redirect()->route('inventory.index')->with('success', 'Detail barang berhasil diperbarui!');
    }

    /**
     * DELETE: Menghapus barang dari inventory
     */
    public function destroy(string $id)
    {
        $myUserId = Auth::id();
        $item = Item::where('user_id', $myUserId)->findOrFail($id);
        $item->delete();

        return redirect()->route('inventory.index')->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * PUBLISH: Mengubah status barang dari draft menjadi available (Masuk Market)
     */
    public function publish(string $id)
    {
        $myUserId = Auth::id();
        $item = Item::where('user_id', $myUserId)->findOrFail($id);

        $item->status = 'available';
        $item->save();

        return redirect()->back()->with('success', 'Barang berhasil diajukan ke Market! Sekarang pengguna lain bisa menawarnya.');
    }
}