<?php
namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;

        if (!$store) {
            return redirect()->route('umkm.profile')
                ->with('info', 'Silakan lengkapi profil toko Anda terlebih dahulu.');
        }

        $totalProducts  = $store->products()->count();
        $totalOrders    = $store->orders()->count() ?? 0;
        $pendingOrders  = $store->orders()->whereIn('status', ['pending', 'confirmed'])->count() ?? 0;
        $totalRevenue   = $store->orders()->whereIn('status', ['completed', 'delivered'])->sum('total') ?? 0;
        
        $recentOrders   = $store->orders()->with(['items.product'])->latest()->take(5)->get();
        $topProducts    = $store->products()->orderBy('created_at', 'desc')->take(5)->get();

        return view('umkm.dashboard', compact(
            'store', 'totalProducts', 'totalOrders',
            'pendingOrders', 'totalRevenue', 'recentOrders', 'topProducts'
        ));
    }

    public function profile()
    {
        $store = Auth::user()->store;
        return view('umkm.profile', compact('store'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'store_name'  => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'address'     => 'required|string|max:500',
            'village'     => 'nullable|string|max:100',
            'district'    => 'nullable|string|max:100',
            'phone'       => 'nullable|string|max:20',
            'whatsapp'    => 'required|string|max:20',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner'      => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'is_active'   => 'boolean',
        ]);

        $store = Auth::user()->store ?? new \App\Models\Store(['user_id' => Auth::id()]);

        $data = $request->only([
            'store_name', 'description', 'address', 
            'village', 'district', 'phone', 'whatsapp', 'is_active'
        ]);
        $data['slug'] = Str::slug($request->store_name);

        if ($request->hasFile('logo')) {
            if ($store->logo) Storage::disk('public')->delete($store->logo);
            $data['logo'] = $request->file('logo')->store('stores/logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($store->banner) Storage::disk('public')->delete($store->banner);
            $data['banner'] = $request->file('banner')->store('stores/banners', 'public');
        }

        $store->fill($data)->save();

        return back()->with('success', 'Profil toko berhasil diperbarui!');
    }
}