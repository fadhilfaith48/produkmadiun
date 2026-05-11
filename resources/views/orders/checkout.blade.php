@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">Checkout Pesanan</h2>

    <div class="row g-4">
        {{-- Ringkasan Produk --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Produk yang dipesan</h5>
                    @foreach($items as $item)
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <img src="{{ $item['product']->image ? asset('storage/'.$item['product']->image) : 'https://via.placeholder.com/60' }}"
                             style="width:60px;height:60px;object-fit:cover;border-radius:8px">
                        <div class="flex-fill">
                            <p class="fw-semibold mb-0">{{ $item['product']->name }}</p>
                            <p class="text-muted small mb-0">{{ $item['product']->formatted_price }} × {{ $item['qty'] }}</p>
                        </div>
                        <p class="fw-bold mb-0">Rp {{ number_format($item['subtotal'],0,',','.') }}</p>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-between fw-bold fs-5 mt-2">
                        <span>Total</span>
                        <span style="color:#2D6A4F">Rp {{ number_format($total,0,',','.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Data Pengiriman --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Data Pengiriman</h5>
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="customer_name" class="form-control"
                                value="{{ auth()->user()->name ?? old('customer_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. HP</label>
                            <input type="text" name="customer_phone" class="form-control"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">No. WhatsApp</label>
                            <input type="text" name="customer_whatsapp" class="form-control"
                                placeholder="08xxxxxxxxxx" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="customer_address" class="form-control" rows="3"
                                placeholder="Jl. ... No. ... Desa ... Kecamatan ..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan (opsional)</label>
                            <input type="text" name="notes" class="form-control"
                                placeholder="Warna, ukuran, dll">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <div class="d-flex flex-column gap-2">
                                <label class="d-flex align-items-center gap-2 p-2 border rounded" style="cursor:pointer">
                                    <input type="radio" name="payment_method" value="whatsapp" checked>
                                    <span>💬 Konfirmasi via WhatsApp</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 p-2 border rounded" style="cursor:pointer">
                                    <input type="radio" name="payment_method" value="transfer">
                                    <span>🏦 Transfer Bank</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 p-2 border rounded" style="cursor:pointer">
                                    <input type="radio" name="payment_method" value="cod">
                                    <span>🏠 COD (Bayar di Tempat)</span>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn w-100 fw-bold text-white"
                            style="background:#2D6A4F;border-radius:8px;padding:12px">
                            Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection