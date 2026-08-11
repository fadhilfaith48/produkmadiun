<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Store;

class ProductController extends Controller
{
    /**
     * Ambil toko milik user yang sedang login.
     */
    private function getStore()
    {
        return Store::where('user_id', Auth::id())->first();
    }

    /**
     * Daftar produk milik toko.
     */
    public function index()
    {
        $store = $this->getStore();

        if (!$store) {
            return redirect()->route('umkm.profile')
                ->with('warning', 'Lengkapi profil toko Anda terlebih dahulu.');
        }

        $products = Product::with('category')
            ->where('store_id', $store->id)
            ->latest()
            ->paginate(15);

        return view('umkm.products.index', compact('products', 'store'));
    }

    /**
     * Form tambah produk.
     */
    public function create()
    {
        $store = $this->getStore();

        if (!$store) {
            return redirect()->route('umkm.profile')
                ->with('warning', 'Lengkapi profil toko Anda terlebih dahulu.');
        }

        $categories = Category::orderBy('name')->get();

        return view('umkm.products.create', compact('categories', 'store'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        $store = $this->getStore();

        if (!$store) {
            return redirect()->route('umkm.profile');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit'        => 'required|string|max:50',
            'description' => 'required|string',
            'weight'      => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
            'images.*'    => 'nullable|image|max:2048',
        ]);

        // Upload foto utama
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Buat slug unik
        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $product = Product::create([
            'store_id'    => $store->id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'unit'        => $request->unit,
            'weight'      => $request->weight ?? 0,
            'image'       => $imagePath,
            'is_active'   => true,
        ]);

        // Upload foto galeri tambahan
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $path = $img->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $path,
                ]);
            }
        }

        return redirect()->route('umkm.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan!');
    }

    /**
     * Form edit produk.
     */
    public function edit($id)
    {
        $store   = $this->getStore();
        $product = Product::where('id', $id)->where('store_id', $store->id)->firstOrFail();
        $categories = Category::orderBy('name')->get();

        return view('umkm.products.edit', compact('product', 'categories', 'store'));
    }

    /**
     * Update produk.
     */
    public function update(Request $request, $id)
    {
        $store   = $this->getStore();
        $product = Product::where('id', $id)->where('store_id', $store->id)->firstOrFail();

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'unit'        => 'required|string|max:50',
            'description' => 'required|string',
            'weight'      => 'nullable|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        // Ganti foto utama kalau ada upload baru
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'unit'        => $request->unit,
            'weight'      => $request->weight ?? 0,
            'is_active'   => $request->has('is_active'),
            'image'       => $product->image,
        ]);

        return redirect()->route('umkm.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk.
     */
    public function destroy($id)
    {
        $store   = $this->getStore();
        $product = Product::where('id', $id)->where('store_id', $store->id)->firstOrFail();

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('umkm.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Upload foto galeri tambahan.
     */
    public function uploadImage(Request $request, $id)
    {
        $store   = $this->getStore();
        $product = Product::where('id', $id)->where('store_id', $store->id)->firstOrFail();

        $request->validate(['image' => 'required|image|max:2048']);

        $path = $request->file('image')->store('products', 'public');
        ProductImage::create(['product_id' => $product->id, 'image' => $path]);

        return back()->with('success', 'Foto berhasil ditambahkan.');
    }

    /**
     * Hapus foto galeri.
     */
    public function deleteImage($imageId)
    {
        $store = $this->getStore();
        $image = ProductImage::whereHas('product', fn($q) => $q->where('store_id', $store->id))
                             ->findOrFail($imageId);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
