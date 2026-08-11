<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'rating'        => 'required|integer|between:1,5',
            'comment'       => 'nullable|string|max:1000',
            'reviewer_name' => Auth::check() ? 'nullable|string|max:100' : 'required|string|max:100',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            abort(404);
        }

        $review = Review::create([
            'product_id'    => $product->id,
            'user_id'       => Auth::id(),
            'reviewer_name' => Auth::check() ? Auth::user()->name : $request->reviewer_name,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
            'is_approved'   => false,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda sedang menunggu persetujuan admin.');
    }
}