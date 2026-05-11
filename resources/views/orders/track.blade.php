@extends('layouts.app')
@section('title', 'Lacak Pesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg border-0" style="border-radius:20px">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-search display-1 text-primary mb-3"></i>
                        <h2 class="fw-bold text-dark mb-1">Lacak Pesanan</h2>
                        <p class="text-muted">Masukkan kode pesanan untuk melacak status</p>
                    </div>

                    @if(session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('order.track.search') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group input-group-lg">
                            <input type="text" name="order_code" class="form-control border-primary"
                                   placeholder="Contoh: ORD-2026-0001"
                                   value="{{ old('order_code') }}" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search me-1"></i>Lacak
                            </button>
                        </div>
                    </form>

                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        Kode pesanan dikirim via WhatsApp setelah checkout
                    </div>
                </div>
            </div>

            {{-- Hasil Pencarian --}}
            @isset($order)
            <div class="card shadow border-0 mt-4" style="border-radius:16px">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $order->order_code }}</h6>
                            <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <span class="badge bg-{{ $order->status_color }} fs-6 px-3 py-2">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <p class="text-muted small mb-3">
                        🏪 {{ $order->store->store_name }}
                    </p>

                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">
                            {{ $item->product_name }}
                            <span class="badge bg-light text-dark">×{{ $item->quantity }}</span>
                        </span>
                        <span class="small fw-semibold">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach

                    <div class="d-flex justify-content-between fw-bold mt-3 fs-6">
                        <span>Total Pembayaran</span>
                        <span style="color:#2D6A4F">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Timeline Status --}}
                    <div class="mt-4 pt-3 border-top">
                        <p class="fw-semibold small mb-3">Status Pengiriman</p>
                        <div class="d-flex flex-column gap-2">
                            @foreach([
                                'pending'    => ['Menunggu Konfirmasi', 'bi-clock',        'warning'],
                                'confirmed'  => ['Dikonfirmasi',        'bi-check-circle', 'info'],
                                'processing' => ['Diproses',            'bi-gear',         'primary'],
                                'shipped'    => ['Dikirim',             'bi-truck',        'secondary'],
                                'completed'  => ['Selesai',             'bi-check-all',    'success'],
                            ] as $status => [$label, $icon, $color])
                            @php
                                $statuses = ['pending','confirmed','processing','shipped','completed'];
                                $currentIdx = array_search($order->status, $statuses);
                                $thisIdx    = array_search($status, $statuses);
                                $isActive   = $thisIdx <= $currentIdx;
                            @endphp
                            <div class="d-flex align-items-center gap-2 {{ $isActive ? '' : 'opacity-25' }}">
                                <i class="bi {{ $icon }} text-{{ $color }}"></i>
                                <span class="small {{ $isActive ? 'fw-semibold' : '' }}">{{ $label }}</span>
                                @if($order->status === $status)
                                    <span class="badge bg-{{ $color }} ms-auto">Sekarang</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endisset

        </div>
    </div>
</div>
@endsection