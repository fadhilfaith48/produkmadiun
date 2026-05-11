<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong!');
        }
        $items = [];
        $total = 0;
        foreach ($cart as $id => $item) {
            $product = Product::with('store')->find($id);
            if ($product) {
                $subtotal = $product->price * $item['qty'];
                $items[]  = ['product' => $product, 'qty' => $item['qty'], 'subtotal' => $subtotal];
                $total   += $subtotal;
            }
        }
        return view('orders.checkout', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required|string|max:100',
            'customer_phone'    => 'required|string|max:20',
            'customer_whatsapp' => 'required|string|max:20',
            'customer_address'  => 'required|string',
            'payment_method'    => 'required|in:cod,transfer,whatsapp',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $firstItem  = reset($cart);
        $storeId    = $firstItem['store_id'];
        $subtotal   = 0;
        $orderItems = [];

        foreach ($cart as $productId => $item) {
            $product   = Product::find($productId);
            $sub       = $product->price * $item['qty'];
            $subtotal += $sub;
            $orderItems[] = [
                'product_id'   => $productId,
                'product_name' => $product->name,
                'price'        => $product->price,
                'quantity'     => $item['qty'],
                'subtotal'     => $sub,
            ];
        }

        $order = Order::create([
            'order_code'        => Order::generateCode(),
    'user_id'    => Auth::id(),
            'store_id'          => $storeId,
            'customer_name'     => $request->customer_name,
            'customer_phone'    => $request->customer_phone,
            'customer_whatsapp' => $request->customer_whatsapp,
            'customer_address'  => $request->customer_address,
            'subtotal'          => $subtotal,
            'shipping_cost'     => 0,
            'total'             => $subtotal,
            'notes'             => $request->notes,
            'payment_method'    => $request->payment_method,
        ]);

        $order->items()->createMany($orderItems);

        foreach ($cart as $productId => $item) {
            Product::find($productId)->decrement('stock', $item['qty']);
        }

        session()->forget('cart');

        if ($request->payment_method === 'whatsapp') {
            return redirect()->route('order.whatsapp', $order->id);
        }

        return redirect()->route('order.success', $order->order_code);
    }

    public function success($code)
    {
        $order = Order::with(['items.product', 'store'])
                    ->where('order_code', $code)->firstOrFail();
        return view('orders.success', compact('order'));
    }

    public function whatsapp($id)
    {
        $order = Order::with(['items', 'store'])->findOrFail($id);
        $store = $order->store;

        $phone = preg_replace('/[^0-9]/', '', $store->whatsapp);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $msg = "Halo *{$store->store_name}*, saya ingin konfirmasi pesanan:\n\n";
        foreach ($order->items as $item) {
            $sub  = number_format($item->subtotal, 0, ',', '.');
            $msg .= "- {$item->product_name} x{$item->quantity} = Rp {$sub}\n";
        }
        $total  = number_format($order->total, 0, ',', '.');
        $msg   .= "\n*Total: Rp {$total}*";
        $msg   .= "\nNama   : {$order->customer_name}";
        $msg   .= "\nAlamat : {$order->customer_address}";
        $msg   .= "\nHP     : {$order->customer_phone}";
        $msg   .= "\nKode   : {$order->order_code}";
        $msg   .= "\n\nMohon konfirmasinya. Terima kasih!";

        return redirect()->away('https://wa.me/' . $phone . '?text=' . urlencode($msg));
    }

    public function track()
    {
        return view('orders.track');
    }

    public function trackSearch(Request $request)
    {
        $request->validate(['order_code' => 'required|string']);
        $order = Order::with(['items.product', 'store'])
                    ->where('order_code', $request->order_code)->first();
        if (!$order) {
            return back()->with('error', 'Kode pesanan tidak ditemukan.');
        }
        return view('orders.track', compact('order'));
    }
}