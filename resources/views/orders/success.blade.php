@extends('layouts.app')
@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 text-center">
            <div class="card border-0 shadow-sm p-4" style="border-radius:16px">
                <div style="font-size:4rem">🎉</div>
                <h3 class="fw-bold mt-3" style="color:#2D6A4F">Pesanan Berhasil!</h3>
                <p class="text-muted">
                    Pesanan kamu sudah kami terima.
                    Simpan kode pesanan ini untuk melacak status.
                </p>

                @foreach($orders as $order)
                <div class="card border-0 mb-3" style="background:#F5F5F5;border-radius:12px">
                    <div class="card-body text-center p-3">
                        @if($orders->count() > 1)
                        <span class="badge mb-2" style="background:#2D6A4F;color:#fff">{{ $order->store->store_name }}</span>
                        @endif
                        <div class="p-3 mb-2" style="background:#E1F5EE;border-radius:10px">
                            <p class="text-muted small mb-1">Kode Pesanan</p>
                            <h4 class="fw-bold mb-0" style="color:#2D6A4F;letter-spacing:2px">
                                {{ $order->order_code }}
                            </h4>
                        </div>

                        <div class="text-start mb-3">
                            <h6 class="fw-bold mb-2">Rincian Pesanan</h6>
                            @foreach($order->items as $item)
                            <div class="d-flex justify-content-between mb-1">
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

                        <a href="{{ route('order.whatsapp', $order->id) }}"
                           class="btn fw-semibold text-white w-100" style="background:#25D366;border-radius:8px">
                            💬 Konfirmasi via WhatsApp
                        </a>
                    </div>
                </div>
                @endforeach

                <div class="mt-2">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection