@extends('layouts.app')
@section('title', 'Daftar Toko - Produk Madiun')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold mb-3">
                <i class="bi bi-shop-window" style="color:#2d6a4f"></i>
                Daftar Toko
            </h1>
            <p class="lead text-muted">Temukan toko favorit Anda di Madiun</p>
            <div class="badge fs-6 px-3 py-2 mt-2" style="background:#2d6a4f">
                {{ $stores->total() }} Toko Tersedia
            </div>
        </div>
    </div>

    {{-- Daftar Toko --}}
    <div class="row g-4 mb-5">
        @forelse($stores as $store)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 shadow border-0 overflow-hidden"
                     style="border-radius:16px;transition:all .3s ease">

                    {{-- Gambar Toko --}}
                    <div class="position-relative overflow-hidden" style="height:200px">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}"
                                 class="w-100 h-100" style="object-fit:cover" alt="{{ $store->store_name }}">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                                 style="background:linear-gradient(135deg,#d8f3dc,#b7e4c7)">
                                <i class="bi bi-shop fs-1" style="color:#2d6a4f"></i>
                            </div>
                        @endif

                        @if($store->is_verified)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge border border-white" style="background:#2d6a4f">
                                    <i class="bi bi-patch-check me-1"></i>Verified
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Info Toko --}}
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-2">{{ $store->store_name }}</h5>

                        <p class="text-muted small mb-3" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                            {{ Str::limit($store->description, 80) }}
                        </p>

                        {{-- Stats --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge fs-6" style="background:#2d6a4f">
                                {{ $store->products_count }} Produk
                            </span>
                            <span class="text-muted small">{{ $store->district }}</span>
                        </div>

                        {{-- Tombol --}}
                        <a href="{{ route('stores.show', $store->slug) }}"
                           class="btn w-100 text-white fw-semibold"
                           style="background:#2d6a4f;border-radius:8px">
                            <i class="bi bi-eye me-2"></i>Lihat Toko
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-shop display-1 text-muted mb-4 d-block"></i>
                <h3 class="text-muted mb-3">Belum ada toko</h3>
                <p class="text-muted lead">Tidak ada toko yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($stores->hasPages())
    <div class="row">
        <div class="col-12">{{ $stores->appends(request()->query())->links() }}</div>
    </div>
    @endif
</div>

@push('styles')
<style>
.card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,.1) !important; }
</style>
@endpush
@endsection
