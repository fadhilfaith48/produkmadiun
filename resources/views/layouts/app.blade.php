<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ProdukMadiun') - Katalog UMKM Kabupaten Madiun</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --pm-green:  #2D6A4F;
            --pm-light:  #52B788;
            --pm-gold:   #F4A261;
            --pm-bg:     #F8FFF8;
        }

        * { box-sizing: border-box; }
        body { background: var(--pm-bg); font-family: 'Segoe UI', sans-serif; margin: 0; padding-bottom: 70px; }

        /* ===== NAVBAR DESKTOP ===== */
        .navbar-pm { background: var(--pm-green) !important; }
        .navbar-pm .navbar-brand { font-weight: 800; color: #fff !important; font-size: 1.3rem; letter-spacing: -0.5px; }
        .navbar-pm .nav-link { color: rgba(255,255,255,0.85) !important; font-size: .9rem; }
        .navbar-pm .nav-link:hover { color: var(--pm-gold) !important; }

        /* ===== BOTTOM NAV MOBILE ===== */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: #fff;
            border-top: 1px solid #e5e7eb;
            z-index: 1050;
            padding: 6px 0 8px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
        }
        .bottom-nav .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .bottom-nav .nav-item-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            text-decoration: none;
            color: #9ca3af;
            font-size: 10px;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 10px;
            transition: all .2s;
            background: none;
            border: none;
            cursor: pointer;
            min-width: 52px;
        }
        .bottom-nav .nav-item-btn i {
            font-size: 1.25rem;
            line-height: 1;
        }
        .bottom-nav .nav-item-btn:hover,
        .bottom-nav .nav-item-btn.active {
            color: var(--pm-green);
        }
        .bottom-nav .nav-item-btn.active i {
            font-weight: 900;
        }
        .bottom-nav .cart-dot {
            position: absolute;
            top: 2px; right: 6px;
            background: var(--pm-gold);
            color: #fff;
            border-radius: 50%;
            width: 16px; height: 16px;
            font-size: 9px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        /* ===== MOBILE MENU OVERLAY ===== */
        .mobile-menu-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 2000;
            backdrop-filter: blur(2px);
        }
        .mobile-menu-overlay.show { display: block; }
        .mobile-menu-panel {
            position: absolute;
            top: 0; right: 0;
            width: 280px; height: 100%;
            background: #fff;
            padding: 0;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform .3s ease;
        }
        .mobile-menu-overlay.show .mobile-menu-panel {
            transform: translateX(0);
        }
        .mobile-menu-header {
            background: var(--pm-green);
            color: #fff;
            padding: 20px 16px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .mobile-menu-header .brand { font-weight: 800; font-size: 1.1rem; }
        .mobile-menu-header .close-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            border-radius: 50%;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 1rem;
        }
        .mobile-menu-links { padding: 8px 0; }
        .mobile-menu-links a,
        .mobile-menu-links button {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 20px;
            text-decoration: none;
            color: #111827;
            font-size: .95rem;
            font-weight: 500;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            transition: background .15s;
        }
        .mobile-menu-links a:hover,
        .mobile-menu-links button:hover {
            background: #f3f4f6;
            color: var(--pm-green);
        }
        .mobile-menu-links a i,
        .mobile-menu-links button i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            color: #374151;
        }
        .mobile-menu-links .divider {
            height: 1px;
            background: #f3f4f6;
            margin: 4px 0;
        }
        .mobile-menu-links .section-label {
            padding: 10px 20px 4px;
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .mobile-search-bar {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .mobile-search-bar form {
            display: flex;
            gap: 8px;
        }
        .mobile-search-bar input {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: .85rem;
            outline: none;
        }
        .mobile-search-bar button {
            background: var(--pm-green);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
        }

        /* ===== SHOW/HIDE RESPONSIVE ===== */
        @media (max-width: 991px) {
            .navbar-pm .collapse { display: none !important; }
            .bottom-nav { display: block; }
            body { padding-bottom: 80px; }
        }

        /* ===== CARDS & PRODUK ===== */
        .product-card { transition: transform .2s, box-shadow .2s; border: none; border-radius: 12px; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
        .product-card .card-img-top { height: 200px; object-fit: cover; border-radius: 12px 12px 0 0; }
        .badge-category { background: var(--pm-light); color: #fff; font-size: .7rem; }
        .btn-pm { background: var(--pm-green); color: #fff; border: none; }
        .btn-pm:hover { background: #1b4332; color: #fff; }
        .btn-wa { background: #25D366; color: #fff; border: none; }
        .btn-wa:hover { background: #128C7E; color: #fff; }
        .cart-badge { position: absolute; top: -6px; right: -8px; background: var(--pm-gold); color: #fff; border-radius: 50%; width: 18px; height: 18px; font-size: .65rem; display: flex; align-items: center; justify-content: center; }
        footer { background: var(--pm-green); color: #fff; }
        footer a { color: rgba(255,255,255,.7); text-decoration: none; }
        footer a:hover { color: var(--pm-gold); }
    </style>
    @stack('styles')
</head>
<body>

{{-- ===== NAVBAR DESKTOP ===== --}}
<nav class="navbar navbar-expand-lg navbar-pm shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.svg') }}" alt="ProdukMadiun" style="width:32px;height:32px">
            ProdukMadiun
        </a>

        {{-- Tombol menu mobile (hanya tampil di mobile, buka slide menu) --}}
        <button class="navbar-toggler border-0 d-lg-none" type="button" onclick="openMobileMenu()" style="background:rgba(255,255,255,0.15);border-radius:8px;padding:6px 10px">
            <i class="bi bi-list text-white fs-5"></i>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="bi bi-grid me-1"></i>Katalog</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('stores.index') }}"><i class="bi bi-shop me-1"></i>Toko UMKM</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('order.track') }}"><i class="bi bi-search me-1"></i>Lacak Pesanan</a></li>
            </ul>

            <form class="d-flex me-3" action="{{ route('products.index') }}" method="GET">
                <input class="form-control form-control-sm" type="search" name="search"
                    placeholder="Cari produk..." value="{{ request('search') }}">
                <button class="btn btn-sm btn-pm ms-1" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <a href="{{ route('cart.index') }}" class="btn btn-outline-light btn-sm me-2 position-relative">
                <i class="bi bi-cart3"></i>
                @php $cartCount = count(session()->get('cart', [])) @endphp
                @if($cartCount > 0)
                    <span class="cart-badge">{{ $cartCount }}</span>
                @endif
            </a>

            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-1">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-sm" style="background:var(--pm-gold);color:#fff">Daftar</a>
            @else
                <div class="dropdown">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if(auth()->user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Panel Admin</a></li>
                        @elseif(auth()->user()->isUmkm())
                            <li><a class="dropdown-item" href="{{ route('umkm.dashboard') }}">
                                <i class="bi bi-shop me-2"></i>Panel UMKM</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </div>
    </div>
</nav>

{{-- ===== MOBILE MENU OVERLAY ===== --}}
<div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="closeMobileMenu()">
    <div class="mobile-menu-panel" onclick="event.stopPropagation()">
        {{-- Header --}}
        <div class="mobile-menu-header">
            <span class="brand d-flex align-items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="ProdukMadiun" style="width:28px;height:28px">
                ProdukMadiun
            </span>
            <button class="close-btn" onclick="closeMobileMenu()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Search --}}
        <div class="mobile-search-bar">
            <form action="{{ route('products.index') }}" method="GET">
                <input type="search" name="search" placeholder="Cari produk..." value="{{ request('search') }}">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        {{-- Menu Links --}}
        <div class="mobile-menu-links">
            <div class="section-label">Menu Utama</div>
            <a href="{{ route('home') }}">
                <i class="bi bi-house-fill"></i> Beranda
            </a>
            <a href="{{ route('products.index') }}">
                <i class="bi bi-grid-fill"></i> Katalog Produk
            </a>
            <a href="{{ route('stores.index') }}">
                <i class="bi bi-shop-window"></i> Toko UMKM
            </a>
            <a href="{{ route('order.track') }}">
                <i class="bi bi-geo-alt-fill"></i> Lacak Pesanan
            </a>
            <a href="{{ route('cart.index') }}">
                <i class="bi bi-cart-fill"></i> Keranjang
                @php $cartCount = count(session()->get('cart', [])) @endphp
                @if($cartCount > 0)
                    <span class="badge ms-auto" style="background:var(--pm-gold)">{{ $cartCount }}</span>
                @endif
            </a>

            <div class="divider"></div>

            @guest
                <div class="section-label">Akun</div>
                <a href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </a>
                <a href="{{ route('register') }}">
                    <i class="bi bi-person-plus-fill"></i> Daftar Akun
                </a>
            @else
                <div class="section-label">Akun: {{ auth()->user()->name }}</div>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Panel Admin
                    </a>
                @elseif(auth()->user()->isUmkm())
                    <a href="{{ route('umkm.dashboard') }}">
                        <i class="bi bi-shop"></i> Dashboard UMKM
                    </a>
                    <a href="{{ route('umkm.products.index') }}">
                        <i class="bi bi-box-seam"></i> Produk Saya
                    </a>
                    <a href="{{ route('umkm.orders.index') }}">
                        <i class="bi bi-bag-check"></i> Pesanan Masuk
                    </a>
                    <a href="{{ route('umkm.profile') }}">
                        <i class="bi bi-pencil-square"></i> Profil Toko
                    </a>
                @endif
                <div class="divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="color:#dc3545">
                        <i class="bi bi-box-arrow-right" style="color:#dc3545"></i> Keluar
                    </button>
                </form>
            @endguest

            <div class="divider"></div>
            <div class="section-label">Untuk UMKM</div>
            <a href="{{ route('register') }}">
                <i class="bi bi-building-add"></i> Daftarkan Toko
            </a>
        </div>
    </div>
</div>

{{-- ===== BOTTOM NAVIGATION MOBILE ===== --}}
<nav class="bottom-nav">
    <div class="nav-items">
        <a href="{{ route('home') }}" class="nav-item-btn {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-house{{ request()->routeIs('home') ? '-fill' : '' }}"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('products.index') }}" class="nav-item-btn {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-grid{{ request()->routeIs('products.*') ? '-fill' : '' }}"></i>
            <span>Katalog</span>
        </a>
        <a href="{{ route('cart.index') }}" class="nav-item-btn position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="bi bi-cart{{ request()->routeIs('cart.*') ? '-fill' : '' }}"></i>
            <span>Keranjang</span>
            @php $cartCount = count(session()->get('cart', [])) @endphp
            @if($cartCount > 0)
                <span class="cart-dot">{{ $cartCount }}</span>
            @endif
        </a>
        <a href="{{ route('stores.index') }}" class="nav-item-btn {{ request()->routeIs('stores.*') ? 'active' : '' }}">
            <i class="bi bi-shop{{ request()->routeIs('stores.*') ? '-window' : '' }}"></i>
            <span>Toko</span>
        </a>
        <button class="nav-item-btn" onclick="openMobileMenu()">
            <i class="bi bi-person-circle"></i>
            <span>
                @auth {{ Str::limit(auth()->user()->name, 6) }}
                @else Akun @endauth
            </span>
        </button>
    </div>
</nav>

{{-- ALERT --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-0 rounded-0" role="alert">
        <div class="container">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-0 rounded-0" role="alert">
        <div class="container">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show mb-0 rounded-0" role="alert">
        <div class="container">
            <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

{{-- KONTEN --}}
@yield('content')

{{-- FOOTER --}}
<footer class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold d-flex align-items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="ProdukMadiun" style="width:28px;height:28px">
                ProdukMadiun
            </h5>
                <p class="small opacity-75">
                    Platform katalog digital UMKM Kabupaten Madiun.
                    Mendukung produk lokal, menggerakkan ekonomi daerah.
                </p>
            </div>
            <div class="col-md-2">
                <h6 class="fw-semibold">Tautan</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('products.index') }}"><i class="bi bi-grid me-1"></i>Katalog</a></li>
                    <li><a href="{{ route('stores.index') }}"><i class="bi bi-shop me-1"></i>Toko UMKM</a></li>
                    <li><a href="{{ route('order.track') }}"><i class="bi bi-geo-alt me-1"></i>Lacak Pesanan</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="fw-semibold">Untuk UMKM</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('register') }}"><i class="bi bi-building-add me-1"></i>Daftarkan Toko</a></li>
                    <li><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Login Penjual</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="fw-semibold">Kontak</h6>
                <p class="small opacity-75 mb-1">
                    <i class="bi bi-geo-alt"></i> Kabupaten Madiun, Jawa Timur
                </p>
                <p class="small opacity-75">
                    <i class="bi bi-envelope"></i> info@produkmadiun.id
                </p>
            </div>
        </div>
        <hr class="opacity-25">
        <p class="text-center small opacity-50 mb-0">
            &copy; {{ date('Y') }} ProdukMadiun — Lomba INOTEK Kabupaten Madiun 2026
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openMobileMenu() {
    const overlay = document.getElementById('mobileMenuOverlay');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    const overlay = document.getElementById('mobileMenuOverlay');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
}

// Tutup menu saat klik link di dalam menu
document.querySelectorAll('.mobile-menu-links a').forEach(link => {
    link.addEventListener('click', closeMobileMenu);
});
</script>

@stack('scripts')
</body>
</html>