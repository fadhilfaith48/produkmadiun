<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;

class PublikController extends Controller
{
    // =====================
    // PRODUK
    // =====================

    public function produk(Request $request)
    {
        $query = Product::with([
                'store:id,store_name,slug,district,whatsapp,logo',
                'category:id,name,slug',
            ])
            ->where('is_active', true)
            ->select('id','store_id','category_id','name','slug',
                     'description','price','stock','unit','image',
                     'weight','views');

        // Search nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // Filter kecamatan (lewat store)
        if ($request->filled('kecamatan')) {
            $query->whereHas('store', function($q) use ($request) {
                $q->where('district', $request->kecamatan);
            });
        }

        $produk = $query->latest()->paginate(12);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data produk UMKM Kabupaten Madiun',
            'data'    => $produk
        ]);
    }

    public function detailProduk($id)
    {
        $produk = Product::with([
                'store:id,store_name,slug,address,district,phone,whatsapp,logo',
                'category:id,name,slug',
            ])
            ->where('is_active', true)
            ->findOrFail($id);

        // Tambah view count
        $produk->increment('views');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail produk',
            'data'    => $produk
        ]);
    }

    // =====================
    // UMKM / TOKO
    // =====================

    public function umkm(Request $request)
    {
        $query = Store::with([
                'products' => function($q) {
                    $q->where('is_active', true)
                      ->select('id','store_id','name','slug','price','image')
                      ->limit(4);
                }
            ])
            ->where('is_verified', true)
            ->where('is_active', true)
            ->select('id','store_name','slug','description',
                     'address','village','district','whatsapp','logo','banner');

        // Filter kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('district', $request->kecamatan);
        }

        // Search nama toko
        if ($request->filled('search')) {
            $query->where('store_name', 'like', '%' . $request->search . '%');
        }

        $umkm = $query->latest()->paginate(10);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data UMKM Kabupaten Madiun',
            'data'    => $umkm
        ]);
    }

    public function detailUmkm($id)
    {
        $toko = Store::with([
                'products' => function($q) {
                    $q->where('is_active', true)
                      ->select('id','store_id','name','slug',
                               'price','stock','unit','image','description');
                }
            ])
            ->where('is_verified', true)
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail toko UMKM',
            'data'    => $toko
        ]);
    }

    // =====================
    // KECAMATAN
    // =====================

    public function kecamatan()
    {
        $kecamatan = Store::where('is_verified', true)
            ->where('is_active', true)
            ->whereNotNull('district')
            ->select('district')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar kecamatan UMKM aktif',
            'data'    => $kecamatan
        ]);
    }

    // =====================
    // KATEGORI
    // =====================

    public function kategori()
    {
        $kategori = Category::withCount([
                'products' => function($q) {
                    $q->where('is_active', true);
                }
            ])
            ->select('id','name','slug','icon')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar kategori produk',
            'data'    => $kategori
        ]);
    }

    // =====================
    // STATISTIK (bonus)
    // =====================

    public function statistik()
    {
        return response()->json([
            'status'  => 'success',
            'message' => 'Statistik platform ProdukMadiun',
            'data'    => [
                'total_umkm'    => Store::where('is_verified', true)->where('is_active', true)->count(),
                'total_produk'  => Product::where('is_active', true)->count(),
                'total_kategori'=> Category::count(),
                'kecamatan'     => Store::where('is_verified', true)
                                    ->whereNotNull('district')
                                    ->distinct('district')
                                    ->count('district'),
            ]
        ]);
    }
}