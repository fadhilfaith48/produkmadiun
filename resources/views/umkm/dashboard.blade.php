{{-- =====================================================
FILE: resources/views/umkm/dashboard.blade.php
===================================================== --}}
@extends('layouts.app')
@section('title', 'Dashboard UMKM')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">🏪 {{ $store->store_name ?? 'Toko Anda' }}</h3>
            <small class="text-muted">Dashboard Pengelolaan Toko</small>
        </div>
        <a href="{{ route('umkm.profile') }}" class="btn text-white"
           style="background:#2d6a4f;border-radius:8px">
            <i class="bi bi-gear me-2"></i>Atur Profil
        </a>
    </div>

    {{-- Shortcut Menu --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-success">
            <i class="bi bi-box-seam me-1"></i> Kelola Produk
        </a>
        <a href="{{ route('umkm.products.create') }}" class="btn text-white"
           style="background:#2d6a4f">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
        </a>
        <a href="{{ route('umkm.orders.index') }}" class="btn btn-outline-success">
            <i class="bi bi-bag-check me-1"></i> Kelola Pesanan
        </a>
    </div>

    {{-- Cek apakah store sudah dibuat --}}
    @if(!$store)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Lengkapi profil toko Anda terlebih dahulu di
        <a href="{{ route('umkm.profile') }}" class="alert-link">halaman profil</a>.
    </div>
    @else

    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">📦</div>
                <h3 class="fw-bold mb-0" style="color:#2d6a4f">{{ $totalProducts }}</h3>
                <small class="text-muted">Total Produk</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">🛍️</div>
                <h3 class="fw-bold mb-0" style="color:#2d6a4f">{{ $totalOrders }}</h3>
                <small class="text-muted">Total Pesanan</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">⏳</div>
                <h3 class="fw-bold mb-0" style="color:#f4a228">{{ $pendingOrders }}</h3>
                <small class="text-muted">Menunggu</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">💰</div>
                <h5 class="fw-bold mb-0" style="color:#2d6a4f">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </h5>
                <small class="text-muted">Pendapatan</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Pesanan terbaru --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="fw-bold mb-0">📋 Pesanan Terbaru</h6>
                        <span class="badge" style="background:#2d6a4f">5 Terbaru</span>
                    </div>
                    @if($recentOrders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="120">Kode</th>
                                    <th>Pelanggan</th>
                                    <th width="120">Total</th>
                                    <th width="100">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td><code>#{{ $order->id }}</code></td>
                                    <td>{{ $order->customer_name ?? 'Pelanggan' }}</td>
                                    <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : 'success' }}">
                                            {{ ucfirst($order->status ?? 'pending') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 mb-3 d-block"></i>
                        Belum ada pesanan
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Produk populer --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">⭐ Produk Populer</h6>
                    @if($topProducts->count() > 0)
                        @foreach($topProducts as $i => $product)
                        <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded bg-light">
                            <span class="badge fs-6" style="background:#2d6a4f">{{ $i + 1 }}</span>
                            <div class="flex-fill">
                                <p class="mb-1 fw-semibold small">{{ Str::limit($product->name, 25) }}</p>
                                <small class="text-muted">
                                    {{ $product->views ?? 0 }} views •
                                    Stok: {{ $product->stock ?? 0 }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-3 text-muted">
                        <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                        <small>Tambahkan produk pertama Anda!</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
