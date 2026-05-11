<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::withCount('products')
                     ->where('is_active', true)
                     ->paginate(12);
                     
        return view('stores.index', compact('stores'));
    }
    
    public function show($slug)
    {
        $store = Store::with(['products' => function($query) {
            $query->where('is_active', true)->latest();
        }])->where('slug', $slug)->firstOrFail();
        
        return view('stores.show', compact('store'));
    }
}