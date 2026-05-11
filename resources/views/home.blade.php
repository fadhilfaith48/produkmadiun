{{-- =====================================================
FILE: resources/views/home.blade.php
Halaman beranda - Modern Redesign
===================================================== --}}
@extends('layouts.app')
@section('title', 'Beranda')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,400&display=swap');

    :root {
        --pm-forest:   #1a3d2b;
        --pm-green:    #2d6a4f;
        --pm-mid:      #40916c;
        --pm-light:    #74c69d;
        --pm-pale:     #d8f3dc;
        --pm-gold:     #f4a228;
        --pm-gold-dk:  #c07d10;
        --pm-cream:    #faf8f4;
        --pm-text:     #1c2b22;
        --pm-muted:    #5a7368;
        --radius-sm:   8px;
        --radius-md:   14px;
        --radius-lg:   22px;
        --radius-xl:   32px;
    }

    *, *::before, *::after { box-sizing: border-box; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--pm-cream);
        color: var(--pm-text);
    }

    /* ── HERO ─────────────────────────────── */
    .pm-hero {
        position: relative;
        background: var(--pm-forest);
        overflow: hidden;
        padding: 100px 0 80px;
        min-height: 540px;
        display: flex;
        align-items: center;
    }

    .pm-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 80% at 80% 50%, rgba(64,145,108,.45) 0%, transparent 70%),
            radial-gradient(ellipse 50% 60% at 10% 90%, rgba(116,198,157,.2) 0%, transparent 60%);
        pointer-events: none;
    }

    /* batik-inspired geometric accent */
    .pm-hero-orb {
        position: absolute;
        right: -60px;
        top: 50%;
        transform: translateY(-50%);
        width: 420px;
        height: 420px;
        border-radius: 50%;
        border: 60px solid rgba(116,198,157,.12);
        box-shadow: 0 0 0 30px rgba(116,198,157,.07);
    }
    .pm-hero-orb::after {
        content: '';
        position: absolute;
        inset: 40px;
        border-radius: 50%;
        border: 2px dashed rgba(116,198,157,.25);
    }

    .pm-hero-leaf {
        position: absolute;
        left: 5%;
        bottom: -20px;
        font-size: 9rem;
        opacity: .07;
        line-height: 1;
        pointer-events: none;
        transform: rotate(-15deg);
    }

    .pm-hero .container {
        position: relative;
        z-index: 2;
    }

    .pm-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(116,198,157,.18);
        border: 1px solid rgba(116,198,157,.35);
        color: var(--pm-light);
        font-size: .75rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 99px;
        margin-bottom: 24px;
    }

    .pm-hero h1 {
        font-family: 'Fraunces', Georgia, serif;
        font-size: clamp(2.6rem, 5vw, 4rem);
        font-weight: 700;
        line-height: 1.15;
        color: #fff;
        margin-bottom: 18px;
    }

    .pm-hero h1 em {
        font-style: italic;
        color: var(--pm-gold);
    }

    .pm-hero p {
        font-size: 1.05rem;
        color: rgba(255,255,255,.68);
        max-width: 500px;
        margin-bottom: 40px;
        line-height: 1.7;
    }

    .pm-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--pm-gold);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: .95rem;
        padding: 14px 30px;
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 4px 16px rgba(244,162,40,.35);
    }
    .pm-btn-primary:hover {
        background: var(--pm-gold-dk);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(244,162,40,.45);
        color: #fff;
    }

    .pm-btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,.1);
        border: 1.5px solid rgba(255,255,255,.35);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        font-size: .95rem;
        padding: 14px 30px;
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: background .2s, border-color .2s;
        backdrop-filter: blur(6px);
    }
    .pm-btn-ghost:hover {
        background: rgba(255,255,255,.18);
        border-color: rgba(255,255,255,.6);
        color: #fff;
    }

    .pm-hero-stats {
        display: flex;
        gap: 36px;
        margin-top: 52px;
        padding-top: 32px;
        border-top: 1px solid rgba(255,255,255,.12);
    }

    .pm-hero-stats .stat-num {
        font-family: 'Fraunces', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .pm-hero-stats .stat-lbl {
        font-size: .75rem;
        color: rgba(255,255,255,.55);
        margin-top: 4px;
        font-weight: 500;
        letter-spacing: .03em;
    }

    /* ── KATEGORI ─────────────────────────── */
    .pm-section { padding: 72px 0; }
    .pm-section-title {
        font-family: 'Fraunces', serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--pm-forest);
        margin-bottom: 8px;
    }
    .pm-section-sub {
        color: var(--pm-muted);
        font-size: .9rem;
        margin-bottom: 36px;
    }

    .pm-cat-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 14px;
    }
    @media (max-width: 991px) { .pm-cat-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 575px)  { .pm-cat-grid { grid-template-columns: repeat(2, 1fr); } }

    .pm-cat-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 22px 12px;
        background: #fff;
        border: 1px solid rgba(45,106,79,.1);
        border-radius: var(--radius-lg);
        text-decoration: none;
        transition: transform .2s, box-shadow .2s, border-color .2s;
        gap: 10px;
    }
    .pm-cat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(45,106,79,.12);
        border-color: var(--pm-light);
    }

    .pm-cat-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: var(--pm-pale);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: background .2s;
    }
    .pm-cat-card:hover .pm-cat-icon { background: #c0e8cc; }

    .pm-cat-name {
        font-weight: 600;
        font-size: .82rem;
        color: var(--pm-forest);
        text-align: center;
        line-height: 1.3;
    }
    .pm-cat-count {
        font-size: .72rem;
        color: var(--pm-muted);
        font-weight: 500;
    }

    /* ── PRODUK CARD ──────────────────────── */
    .pm-product-card {
        background: #fff;
        border-radius: var(--radius-lg);
        border: 1px solid rgba(45,106,79,.08);
        overflow: hidden;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: transform .2s, box-shadow .2s;
    }
    .pm-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(45,106,79,.13);
    }

    .pm-product-img {
        aspect-ratio: 1;
        background: var(--pm-pale);
        object-fit: cover;
        width: 100%;
    }

    .pm-product-body {
        padding: 14px 16px 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .pm-product-name {
        font-weight: 600;
        font-size: .9rem;
        color: var(--pm-text);
        margin-bottom: 4px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .pm-product-store {
        font-size: .75rem;
        color: var(--pm-muted);
        margin-bottom: 10px;
    }

    .pm-product-price {
        font-family: 'Fraunces', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--pm-green);
        margin-top: auto;
    }

    .pm-product-badge {
        display: inline-block;
        background: #fff3e0;
        color: #c07d10;
        font-size: .65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 99px;
        margin-bottom: 10px;
        letter-spacing: .04em;
    }

    /* ── PRODUK GRID ──────────────────────── */
    .pm-product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
    }
    @media (max-width: 991px) { .pm-product-grid { grid-template-columns: repeat(2, 1fr); } }

    /* ── TOKO ─────────────────────────────── */
    .pm-stores-bg {
        background: linear-gradient(160deg, #e9f5ee 0%, #f4faf6 100%);
        padding: 72px 0;
    }

    .pm-store-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 767px) { .pm-store-grid { grid-template-columns: 1fr; } }

    .pm-store-card {
        background: #fff;
        border: 1px solid rgba(45,106,79,.1);
        border-radius: var(--radius-lg);
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        text-decoration: none;
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .pm-store-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 28px rgba(45,106,79,.12);
        border-color: var(--pm-mid);
    }

    .pm-store-avatar {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--pm-mid), var(--pm-light));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .pm-store-name {
        font-weight: 700;
        font-size: .9rem;
        color: var(--pm-forest);
        margin-bottom: 2px;
    }
    .pm-store-meta {
        font-size: .75rem;
        color: var(--pm-muted);
        margin-bottom: 6px;
    }
    .pm-verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: #d8f3dc;
        color: var(--pm-green);
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .03em;
        padding: 3px 9px;
        border-radius: 99px;
    }

    /* ── CTA STRIP ────────────────────────── */
    .pm-cta-strip {
        background: var(--pm-forest);
        padding: 64px 0;
        position: relative;
        overflow: hidden;
    }
    .pm-cta-strip::before {
        content: '';
        position: absolute;
        right: -80px;
        top: -80px;
        width: 340px;
        height: 340px;
        border-radius: 50%;
        background: rgba(116,198,157,.08);
        pointer-events: none;
    }

    .pm-cta-strip h2 {
        font-family: 'Fraunces', serif;
        font-size: 2.1rem;
        color: #fff;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .pm-cta-strip p {
        color: rgba(255,255,255,.6);
        font-size: .95rem;
        margin-bottom: 0;
    }

    /* ── SECTION HEADER ROW ───────────────── */
    .pm-sec-hdr { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 32px; }
    .pm-link-more {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--pm-green);
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1.5px solid transparent;
        transition: border-color .15s;
        white-space: nowrap;
    }
    .pm-link-more:hover { border-color: var(--pm-green); color: var(--pm-green); }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="pm-hero">
    <div class="pm-hero-orb"></div>
    <div class="pm-hero-leaf">🌿</div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="pm-hero-badge">
                    <i class="bi bi-patch-check-fill"></i>
                    Platform UMKM Resmi Kabupaten Madiun
                </div>
                <h1>Produk Lokal Madiun, <em>Kualitas Terbaik</em></h1>
                <p>Temukan ribuan produk UMKM unggulan dari seluruh pelosok Kabupaten Madiun — langsung dari tangan pengrajin lokal.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products.index') }}" class="pm-btn-primary">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                        Lihat Katalog
                    </a>
                    <a href="{{ route('register') }}" class="pm-btn-ghost">
                        <i class="bi bi-shop"></i>
                        Daftarkan Toko UMKM
                    </a>
                </div>
                <div class="pm-hero-stats">
                    <div>
                        <div class="stat-num">1.200+</div>
                        <div class="stat-lbl">Produk tersedia</div>
                    </div>
                    <div>
                        <div class="stat-num">340+</div>
                        <div class="stat-lbl">Toko terverifikasi</div>
                    </div>
                    <div>
                        <div class="stat-num">28</div>
                        <div class="stat-lbl">Kecamatan terwakili</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- KATEGORI --}}
<section class="pm-section" style="background:#fff;">
    <div class="container">
        <div class="pm-sec-hdr">
            <div>
                <h2 class="pm-section-title">Belanja Berdasarkan Kategori</h2>
                <p class="pm-section-sub mb-0">Jelajahi produk pilihan sesuai kebutuhanmu</p>
            </div>
        </div>
        <div class="pm-cat-grid">
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="pm-cat-card">
                <div class="pm-cat-icon">{{ $cat->icon }}</div>
                <span class="pm-cat-name">{{ $cat->name }}</span>
                <span class="pm-cat-count">{{ $cat->products_count }} produk</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- PRODUK TERPOPULER --}}
<section class="pm-section" style="background: var(--pm-cream);">
    <div class="container">
        <div class="pm-sec-hdr">
            <div>
                <h2 class="pm-section-title">Produk Terpopuler</h2>
                <p class="pm-section-sub mb-0">Favorit para pembeli minggu ini</p>
            </div>
            <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="pm-link-more">
                Lihat semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="pm-product-grid">
            @foreach($featuredProducts as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="pm-product-card">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="pm-product-img">
                @else
                    <div class="pm-product-img d-flex align-items-center justify-content-center" style="font-size:3rem;">🛍️</div>
                @endif
                <div class="pm-product-body">
                    @if($product->is_featured ?? false)
                        <span class="pm-product-badge">⭐ Unggulan</span>
                    @endif
                    <div class="pm-product-name">{{ $product->name }}</div>
                    <div class="pm-product-store">
                        <i class="bi bi-shop me-1"></i>{{ $product->store->store_name ?? 'Toko UMKM' }}
                    </div>
                    <div class="pm-product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- TOKO TERVERIFIKASI --}}
<section class="pm-stores-bg">
    <div class="container">
        <div class="pm-sec-hdr">
            <div>
                <h2 class="pm-section-title">Toko UMKM Terverifikasi</h2>
                <p class="pm-section-sub mb-0">Dipercaya ribuan pembeli se-Madiun</p>
            </div>
            <a href="{{ route('stores.index') }}" class="pm-link-more">
                Semua toko <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="pm-store-grid">
            @foreach($verifiedStores as $store)
            <a href="{{ route('stores.show', $store->slug) }}" class="pm-store-card">
                <div class="pm-store-avatar">🏪</div>
                <div style="min-width:0">
                    <div class="pm-store-name">{{ $store->store_name }}</div>
                    <div class="pm-store-meta">{{ $store->district }} &middot; {{ $store->products_count }} produk</div>
                    <span class="pm-verified-badge">
                        <i class="bi bi-patch-check-fill" style="font-size:.7rem"></i>
                        Terverifikasi
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="pm-cta-strip">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-md-7">
                <h2>Punya produk lokal Madiun?</h2>
                <p>Bergabunglah bersama ratusan pelaku UMKM dan jangkau lebih banyak pembeli.</p>
            </div>
            <div class="col-md-5 text-md-end">
                <a href="{{ route('register') }}" class="pm-btn-primary">
                    <i class="bi bi-shop-window"></i>
                    Daftar Gratis Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

@endsection