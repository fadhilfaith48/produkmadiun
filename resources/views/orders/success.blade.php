@extends('layouts.app')
@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card border-0 shadow-sm p-4" style="border-radius:16px">
                <div style="font-size:4rem">🎉</div>
                <h3 class="fw-bold mt-3" style="color:#2D6A4F">Pesanan Berhasil!</h3>
                <p class="text-muted">Pesanan kamu sudah kami terima. Simpan kode pesanan ini untuk melacak status.</p>

                <div class="p-3 mb-3" style="background:#E1F5EE;border-radius:10px">
                    <p class="text-muted small mb-1">Kode Pesanan</p>
                    <h4 class="fw-bold mb-0" style="color:#2D6A4F;letter-spacing:2px">
                        {{ $order->order_code }}
                    </h4>
                </div>

                <div class="text-start mb-4">
                    <h6 class="fw-bold mb-3">Rincian Pesanan</h6>
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">{{ $item->product_name }} ×{{ $item->quantity }}</span>
                        <span>Rp {{ number_format($item->subtotal,0,',','.') }}</span>
                    </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span style="color:#2D6A4F">Rp {{ number_format($order->total,0,',','.') }}</span>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('order.whatsapp', $order->id) }}"
                       class="btn fw-semibold text-white" style="background:#25D366;border-radius:8px">
                        💬 Konfirmasi via WhatsApp
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="border-radius:8px">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection