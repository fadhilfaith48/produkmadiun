@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Katalog</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Foto Produk --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius:12px;overflow:hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         class="img-fluid w-100"
                         style="height:380px;object-fit:cover"
                         alt="{{ $product->name }}">
                @else
                    <div style="height:380px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:4rem">📦</div>
                @endif
            </div>

            {{-- Foto galeri --}}
            @if($product->images->count() > 0)
            <div class="d-flex gap-2 mt-2 flex-wrap">
                @foreach($product->images as $img)
                <img src="{{ asset('storage/' . $img->image) }}"
                     style="width:72px;height:72px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid #ddd"
                     onclick="document.querySelector('.main-img').src=this.src">
                @endforeach
            </div>
            @endif
        </div>

        {{-- Info Produk --}}
        <div class="col-md-7">
            <span class="badge mb-2" style="background:#d8f3dc;color:#2d6a4f">
                {{ $product->category->name ?? 'Umum' }}
            </span>
            <h2 class="fw-bold mb-1">{{ $product->name }}</h2>

            <div class="d-flex align-items-center gap-3 mb-3">
                <h3 class="fw-bold mb-0" style="color:#2d6a4f">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </h3>
                <span class="text-muted">/ {{ $product->unit }}</span>
            </div>

            {{-- Rating --}}
            @if(($product->reviews_count ?? 0) > 0)
            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="color:#f4a228">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <span class="text-muted small">{{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }} ulasan)</span>
            </div>
            @endif

            <hr>

            {{-- Detail --}}
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <small class="text-muted d-block">Stok</small>
                    <span class="fw-semibold">{{ $product->stock }} {{ $product->unit }}</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Berat</small>
                    <span class="fw-semibold">{{ $product->weight ?? 0 }} gram</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Toko</small>
                    <a href="{{ route('stores.show', $product->store->slug) }}" class="fw-semibold text-decoration-none" style="color:#2d6a4f">
                        {{ $product->store->store_name }}
                    </a>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block">Lokasi</small>
                    <span class="fw-semibold">{{ $product->store->district ?? $product->store->village ?? '-' }}</span>
                </div>
            </div>

            <hr>

            {{-- Deskripsi --}}
            <h6 class="fw-bold">Deskripsi</h6>
            <p class="text-muted" style="line-height:1.8">{{ $product->description }}</p>

            {{-- Tombol Aksi --}}
            <div class="d-flex gap-2 mt-4 flex-wrap">
                @auth
                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-outline-success px-4">
                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                    </button>
                </form>
                @endauth

                @if($product->store->whatsapp)
                <a href="{{ $product->getWhatsappOrderLink() }}" target="_blank"
                   class="btn px-4 text-white" style="background:#25d366">
                    <i class="bi bi-whatsapp me-1"></i> Pesan via WhatsApp
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Ulasan --}}
    @if($product->reviews->count() > 0)
    <div class="mt-5">
        <h5 class="fw-bold mb-3">⭐ Ulasan Pelanggan</h5>
        <div class="row g-3">
            @foreach($product->reviews as $review)
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-3" style="border-radius:10px">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold">{{ $review->reviewer_name ?: ($review->user->name ?? 'Anonim') }}</span>
                        <div style="color:#f4a228">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} small"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="mb-0 text-muted small">{{ $review->comment }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Form Ulasan --}}
    <div class="card border-0 shadow-sm p-4 mt-4" style="border-radius:10px">
        <h5 class="fw-bold mb-3">💬 Tulis Ulasan</h5>
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="mb-3">
                <label class="form-label fw-semibold">Rating</label>
                <div class="d-flex gap-3">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rating" value="{{ $i }}"
                               id="rate{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                        <label class="form-check-label" for="rate{{ $i }}" style="color:#f4a228">★ {{ $i }}</label>
                    </div>
                    @endfor
                </div>
                @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            @guest
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Anda</label>
                <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}"
                       class="form-control @error('reviewer_name') is-invalid @enderror">
                @error('reviewer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            @endguest
            <div class="mb-3">
                <label class="form-label fw-semibold">Komentar <span class="text-muted">(opsional)</span></label>
                <textarea name="comment" rows="3" class="form-control"
                          placeholder="Bagikan pengalaman Anda...">{{ old('comment') }}</textarea>
            </div>
            <button type="submit" class="btn text-white px-4" style="background:#2d6a4f">
                Kirim Ulasan
            </button>
        </form>
    </div>

    {{-- Produk Terkait --}}
    @if($related->count() > 0)
    <div class="mt-5">
        <h5 class="fw-bold mb-3">🛍️ Produk Terkait</h5>
        <div class="row g-3">
            @foreach($related as $item)
            <div class="col-6 col-md-3">
                <a href="{{ route('products.show', $item->slug) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:10px;overflow:hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 style="height:150px;object-fit:cover" class="w-100">
                        @else
                            <div style="height:150px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:2rem">📦</div>
                        @endif
                        <div class="card-body p-2">
                            <p class="mb-1 small fw-semibold text-dark">{{ Str::limit($item->name, 40) }}</p>
                            <p class="mb-0 fw-bold" style="color:#2d6a4f">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
