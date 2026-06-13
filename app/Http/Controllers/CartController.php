<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('item.user')->where('user_id', Auth::id())->latest()->get();
        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $exists = Cart::where('user_id', Auth::id())->where('item_id', $request->item_id)->exists();
        
        if ($exists) {
            return redirect()->back()->with('error', 'Barang sudah ada di keranjang kamu.');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id
        ]);
        return redirect()->back()->with('success', 'Barang berhasil disimpan ke keranjang!');
    }

    public function destroy(string $id)
    {
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang.');
    }
}