<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->latest()->paginate(20);

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'image'     => 'required|image|max:2048',
            'link'      => 'nullable|url|max:255',
            'is_active' => 'nullable',
            'order'     => 'nullable|integer|min:0',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title'     => $request->title,
            'image'     => $imagePath,
            'link'      => $request->link,
            'is_active' => $request->has('is_active'),
            'order'     => $request->order ?? 0,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title'     => 'required|string|max:255',
            'image'     => 'nullable|image|max:2048',
            'link'      => 'nullable|url|max:255',
            'is_active' => 'nullable',
            'order'     => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->update([
            'title'     => $request->title,
            'link'      => $request->link,
            'is_active' => $request->has('is_active'),
            'order'     => $request->order ?? 0,
            'image'     => $banner->image,
        ]);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus.');
    }
}
