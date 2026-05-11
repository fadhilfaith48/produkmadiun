<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Tampilkan halaman keranjang
    public function index()
    {
        $cart  = Session::get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $subtotal = $product->price * $item['qty'];
                $items[]  = [
                    'product'  => $product,
                    'qty'      => $item['qty'],
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }
        }

        return view('cart.index', compact('items', 'total'));
    }

    // Tambah produk ke keranjang
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi!');
        }

        $cart = Session::get('cart', []);
        $id   = $request->product_id;

        if (isset($cart[$id])) {
            // Kalau sudah ada, tambah qty-nya
            $cart[$id]['qty'] += $request->qty;
        } else {
            // Kalau belum ada, buat entry baru
            // ✅ store_id disimpan di sini — dipakai saat checkout
            $cart[$id] = [
                'qty'      => $request->qty,
                'store_id' => $product->store_id,
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update jumlah produk di keranjang
    public function update(Request $request, $id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $qty = max(1, (int) $request->qty);
            $cart[$id]['qty'] = $qty;
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    // Hapus satu produk dari keranjang
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    // Kosongkan semua keranjang
    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan!');
    }
}