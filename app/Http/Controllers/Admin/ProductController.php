<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['store', 'category'])->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with(['store', 'category', 'images'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product    = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->only([
            'name', 'category_id', 'price', 'stock', 'is_active'
        ]));
        return redirect()->route('admin.dashboard')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}