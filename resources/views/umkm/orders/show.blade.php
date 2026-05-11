@extends('layouts.app')
@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">📋 Detail Pesanan</h3>
            <small class="text-muted">{{ $order->order_code ?? '#'.$order->id }}</small>
        </div>
        <a href="{{ route('umkm.orders.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        {{-- Info Pesanan --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius:12px">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">🛒 Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Ongkir</td>
                                    <td class="text-end">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Info Pengiriman --}}
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">📦 Info Pengiriman</h6>
                    <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                    <p class="mb-1"><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                    @if($order->notes)
                    <p class="mb-0"><strong>Catatan:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status & Aksi --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">⚙️ Update Status</h6>
                    <p class="mb-2">Status saat ini:
                        <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    </p>
                    <p class="mb-3 text-muted small">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>

                    <form action="{{ route('umkm.orders.update-status', $order->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                @foreach(['pending'=>'Menunggu Pembayaran','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $val => $label)
                                <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
