{{-- =====================================================
FILE: resources/views/cart/index.blade.php
Halaman keranjang belanja - VERSI FIX
===================================================== --}}
@extends('layouts.app')
@section('title', 'Keranjang Belanja')
 
@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h2>
 
    @if(empty($items) || count($items) == 0)
        <div class="text-center py-5">
            <div style="font-size:5rem">🛒</div>
            <p class="text-muted fs-5">Keranjang masih kosong</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Belanja Sekarang</a>
        </div>
    @else
    <div class="row g-4">
        <div class="col-md-8">
            @foreach($items as $item)
            <div class="card border-0 shadow-sm mb-3" style="border-radius:12px">
                <div class="card-body d-flex align-items-center gap-3">
                    <img src="{{ $item['product']->image ? asset('storage/'.$item['product']->image) : asset('images/no-image.png') }}"
                         style="width:80px;height:80px;object-fit:cover;border-radius:8px" alt="{{ $item['product']->name }}">
                    <div class="flex-fill">
                        <h6 class="fw-semibold mb-1">{{ Str::limit($item['product']->name, 50) }}</h6>
                        {{-- FIX: Gunakan store_id atau relasi yang benar --}}
                        <p class="text-muted small mb-1">
                            {{ $item['product']->store ? $item['product']->store->name : 'Toko Umum' }}
                        </p>
                        <p class="fw-bold mb-0 text-success">
                            Rp {{ number_format($item['product']->price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        {{-- UPDATE QUANTITY --}}
                        <form action="{{ route('cart.update', $item['product']->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="number" name="qty" value="{{ $item['qty'] }}"
                                   min="1" max="{{ $item['product']->stock }}"
                                   class="form-control form-control-sm text-center"
                                   style="width:70px"
                                   onchange="this.form.submit()">
                        </form>
                        
                        {{-- SUBTOTAL --}}
                        <p class="fw-bold mb-0 text-nowrap">
                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </p>
                        
                        {{-- HAPUS ITEM --}}
                        <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" 
                                    onclick="return confirm('Hapus {{ $item['product']->name }} dari keranjang?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
 
        {{-- RINGKASAN --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px;position:sticky;top:80px">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal ({{ count($items) }} item)</span>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Ongkir</span>
                        <span class="text-success">Negosiasi via WA</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total Belanja</strong>
                        <strong class="text-success fs-4">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </strong>
                    </div>
                    <a href="{{ route('order.checkout') }}" class="btn btn-success w-100 fw-semibold mb-2">
                        <i class="bi bi-credit-card me-2"></i>Checkout Sekarang
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-shop me-2"></i>Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection