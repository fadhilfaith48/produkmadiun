<?php
// =====================================================
// FILE: app/Http/Controllers/HomeController.php
// =====================================================
namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use App\Models\Banner;
 
class HomeController extends Controller
{
    public function index()
    {
        $banners        = Banner::where('is_active', true)->orderBy('order')->get();
        $categories     = Category::withCount('products')->get();
        $featuredProducts = Product::with(['store', 'category'])
                            ->where('is_active', true)
                            ->orderBy('views', 'desc')
                            ->take(8)->get();
        $newProducts    = Product::with(['store', 'category'])
                            ->where('is_active', true)
                            ->latest()->take(8)->get();
                            
        // ✅ UDAH AMAN! Tabel stores sudah ada
        $verifiedStores = Store::where('is_verified', true)
                            ->where('is_active', true)
                            ->withCount('products')
                            ->take(6)->get();
 
        return view('home', compact(
            'banners','categories','featuredProducts','newProducts','verifiedStores'
        ));
    }
}