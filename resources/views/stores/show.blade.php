{{-- =====================================================
FILE: resources/views/stores/show.blade.php
Halaman detail TOKO + daftar produk
===================================================== --}}
@extends('layouts.app')
@section('title', $store->store_name)

@section('content')
<div class="container py-4">
    {{-- BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">Toko</a></li>
            <li class="breadcrumb-item active">{{ $store->store_name }}</li>
        </ol>
    </nav>

    {{-- INFO TOKO --}}
    <div class="row mb-5">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div style="font-size:3rem">🏪</div>
                            @if($store->logo)
                            <img src="{{ asset('storage/'.$store->logo) }}" 
                                 class="rounded-circle mt-2" style="width:80px;height:80px;object-fit:cover">
                            @endif
                        </div>
                        <div class="col-md-10">
                            <h1 class="fw-bold mb-1">{{ $store->store_name }}</h1>
                            <p class="text-muted mb-2">{{ $store->description }}</p>
                            <div class="d-flex gap-3 flex-wrap">
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    {{ $store->products->where('is_active', true)->count() }} Produk
                                </span>
                                <span class="badge bg-info fs-6 px-3 py-2">
                                    {{ $store->district }}, Madiun
                                </span>
                                @if($store->is_verified)
                                <span class="badge bg-warning fs-6 px-3 py-2">✓ Terverifikasi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tombol WhatsApp --}}
                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ $store->whatsapp_link }}" target="_blank"
                           class="btn btn-wa btn-lg px-4 me-2">
                            <i class="bi bi-whatsapp me-2"></i>Chat Toko
                        </a>
                        <a href="{{ route('stores.show', $store->slug) }}#reviews" 
                           class="btn btn-outline-secondary btn-lg px-4">
                            Lihat Ulasan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR PRODUK --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-box-seam me-2"></i>
                    Produk ({{ $store->products->where('is_active', true)->count() }})
                </h3>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @forelse($store->products->where('is_active', true) as $product)
            <div class="col-xl-3 col-lg-4 col-md-6">
                @include('partials.product-card', ['product' => $product])
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div style="font-size:4rem" class="text-muted mb-3">📦</div>
                    <h4 class="text-muted mb-3">Belum ada produk</h4>
                    <p class="text-muted">Toko ini belum menambahkan produk apa pun.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection