@extends('layouts.app')
@section('title', 'Katalog Produk')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">🛍️ Katalog Produk</h3>
            <small class="text-muted">{{ $products->total() }} produk tersedia</small>
        </div>
    </div>

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control"
                       placeholder="Cari produk..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="">Terbaru</option>
                    <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                    <option value="popular"    {{ request('sort') == 'popular'    ? 'selected' : '' }}>Terpopuler</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn w-100 text-white" style="background:#2d6a4f">Cari</button>
            </div>
        </div>
    </form>

    @if($products->count() > 0)
    <div class="row g-3">
        @foreach($products as $product)
        <div class="col-6 col-md-3">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100" style="border-radius:12px;overflow:hidden;transition:transform .2s"
                     onmouseover="this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    {{-- Foto --}}
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             style="height:180px;object-fit:cover;width:100%"
                             alt="{{ $product->name }}">
                    @else
                        <div style="height:180px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:2.5rem">📦</div>
                    @endif

                    <div class="card-body p-3">
                        <p class="mb-1 fw-semibold text-dark" style="font-size:.9rem;line-height:1.3">
                            {{ Str::limit($product->name, 45) }}
                        </p>
                        <p class="mb-1 text-muted" style="font-size:.78rem">
                            {{ $product->store->store_name ?? '-' }}
                        </p>
                        <p class="mb-0 fw-bold" style="color:#2d6a4f">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    @else
    <div class="text-center py-5">
        <div style="font-size:3rem">🔍</div>
        <h5 class="mt-3 fw-bold">Produk tidak ditemukan</h5>
        <p class="text-muted">Coba kata kunci atau kategori lain</p>
        <a href="{{ route('products.index') }}" class="btn btn-outline-success">Reset Filter</a>
    </div>
    @endif

</div>
@endsection
