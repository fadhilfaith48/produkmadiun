@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4">Dashboard Admin ProdukMadiun</h3>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.stores') }}" class="btn btn-outline-success btn-sm">🏪 Kelola Toko</a>
        <a href="{{ route('admin.reviews') }}" class="btn btn-outline-success btn-sm">💬 Kelola Ulasan</a>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-success btn-sm">🎯 Kelola Banner</a>
    </div>

    {{-- Statistik --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">📦</div>
                <h3 class="fw-bold mb-0" style="color:#2D6A4F">{{ $stats['total_products'] }}</h3>
                <small class="text-muted">Total Produk</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">🏪</div>
                <h3 class="fw-bold mb-0" style="color:#2D6A4F">{{ $stats['total_stores'] }}</h3>
                <small class="text-muted">Total Toko</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">🛍️</div>
                <h3 class="fw-bold mb-0" style="color:#2D6A4F">{{ $stats['total_orders'] }}</h3>
                <small class="text-muted">Total Pesanan</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center py-3" style="border-radius:12px">
                <div style="font-size:2rem">⏳</div>
                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_orders'] }}</h3>
                <small class="text-muted">Pesanan Pending</small>
            </div>
        </div>
    </div>

    {{-- Pesanan Terbaru --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Pesanan Terbaru</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th><th>Toko</th><th>Total</th><th>Status</th><th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td><code>{{ $order->order_code }}</code></td>
                            <td>{{ $order->store->store_name ?? '-' }}</td>
                            <td>Rp {{ number_format($order->total,0,',','.') }}</td>
                            <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                            <td class="text-muted small">{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Toko Pending Verifikasi --}}
    @if($stats['pending_stores'] > 0)
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <span>⏳ Ada <strong>{{ $stats['pending_stores'] }}</strong> toko menunggu verifikasi.</span>
        <a href="{{ route('admin.stores') }}" class="btn btn-warning btn-sm fw-bold">Kelola Toko →</a>
    </div>
    @else
    <div class="text-end mb-2">
        <a href="{{ route('admin.stores') }}" class="btn btn-outline-success btn-sm">🏪 Kelola Semua Toko</a>
    </div>
    @endif
</div>
@endsection