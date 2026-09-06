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

        $cart = Session::get('cart', []);
        $id   = $request->product_id;

        $currentQty = $cart[$id]['qty'] ?? 0;
        $newQty     = $currentQty + (int) $request->qty;

        if ($newQty > $product->stock) {
            $sisa = max(0, $product->stock - $currentQty);
            return back()->with('error', 'Stok tidak mencukupi! ' . ($sisa > 0 ? 'Sisa ' . $sisa . ' ' . $product->unit . '.' : 'Stok sudah habis.'));
        }

        $cart[$id] = [
            'qty'      => $newQty,
            // ✅ store_id disimpan di sini — dipakai saat checkout
            'store_id' => $product->store_id,
        ];

        Session::put('cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update jumlah produk di keranjang
    public function update(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:100',
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::find($id);

            if (!$product || !$product->is_active) {
                unset($cart[$id]);
                Session::put('cart', $cart);
                return back()->with('error', 'Produk tidak tersedia lagi dan dihapus dari keranjang.');
            }

            if ($request->qty > $product->stock) {
                return back()->with('error', 'Stok tidak mencukupi! Maksimal ' . $product->stock . ' ' . $product->unit . '.');
            }

            $cart[$id]['qty'] = (int) $request->qty;
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