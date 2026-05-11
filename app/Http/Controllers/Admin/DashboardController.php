<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => \App\Models\Product::count(),
            'total_stores'   => \App\Models\Store::count(),
            'total_orders'   => \App\Models\Order::count(),
            'total_revenue'  => \App\Models\Order::where('status', 'completed')->sum('total'),
            'pending_orders' => \App\Models\Order::where('status', 'pending')->count(),
            'pending_stores' => Store::where('is_verified', false)->count(),
        ];

        $recentOrders = \App\Models\Order::with(['store'])->latest()->take(10)->get();
        $topProducts  = \App\Models\Product::orderBy('views', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }

    public function approveReview($id)
    {
        Review::findOrFail($id)->update(['is_approved' => true]);
        return back()->with('success', 'Ulasan disetujui.');
    }

    /**
     * Halaman kelola toko — daftar semua toko + status verifikasi.
     */
    public function stores()
    {
        $stores = Store::with('user')
            ->orderBy('is_verified', 'asc') // yang belum verifikasi muncul duluan
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.stores', compact('stores'));
    }

    /**
     * Verifikasi toko.
     */
    public function verifyStore($id)
    {
        Store::findOrFail($id)->update(['is_verified' => true, 'is_active' => true]);
        return back()->with('success', 'Toko berhasil diverifikasi.');
    }

    /**
     * Batalkan verifikasi toko.
     */
    public function unverifyStore($id)
    {
        Store::findOrFail($id)->update(['is_verified' => false]);
        return back()->with('success', 'Verifikasi toko dibatalkan.');
    }
}
