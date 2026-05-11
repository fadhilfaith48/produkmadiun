<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    private function getStore()
    {
        return Auth::user()->store;
    }

    public function index()
    {
        $store = $this->getStore();

        if (!$store) {
            return redirect()->route('umkm.profile')
                ->with('info', 'Silakan lengkapi profil toko Anda terlebih dahulu.');
        }

        $orders = $store->orders()
            ->with(['items.product', 'user'])
            ->latest()
            ->paginate(15);

        return view('umkm.orders.index', compact('store', 'orders'));
    }

    public function show($id)
    {
        $store = $this->getStore();
        $order = Order::with(['items.product', 'user'])
            ->where('store_id', $store->id)
            ->findOrFail($id);

        return view('umkm.orders.show', compact('store', 'order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $store = $this->getStore();
        $order = Order::where('store_id', $store->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
