@extends('layouts.app')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">🛍️ Kelola Pesanan</h3>
            <small class="text-muted">{{ $store->store_name }}</small>
        </div>
        <a href="{{ route('umkm.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($orders->count() > 0)
    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><code>{{ $order->order_code ?? '#'.$order->id }}</code></td>
                            <td>
                                <p class="mb-0 fw-semibold">{{ $order->customer_name }}</p>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $order->items->count() }} item
                                    @if($order->items->count() > 0)
                                        — {{ $order->items->first()->product_name }}
                                        @if($order->items->count() > 1)
                                            +{{ $order->items->count() - 1 }} lainnya
                                        @endif
                                    @endif
                                </small>
                            </td>
                            <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td><small>{{ $order->created_at->format('d M Y') }}</small></td>
                            <td>
                                <a href="{{ route('umkm.orders.show', $order->id) }}"
                                   class="btn btn-sm btn-outline-primary">Detail</a>

                                {{-- Update status --}}
                                <div class="dropdown d-inline">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown">Status</button>
                                    <ul class="dropdown-menu">
                                        @foreach(['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $val => $label)
                                        <li>
                                            <form action="{{ route('umkm.orders.update-status', $order->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="{{ $val }}">
                                                <button class="dropdown-item {{ $order->status == $val ? 'fw-bold text-success' : '' }}">
                                                    {{ $label }}
                                                </button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links() }}</div>

    @else
    <div class="card border-0 shadow-sm text-center py-5" style="border-radius:12px">
        <div style="font-size:3rem">🛍️</div>
        <h5 class="mt-3 fw-bold">Belum ada pesanan</h5>
        <p class="text-muted">Pesanan dari pelanggan akan muncul di sini</p>
    </div>
    @endif
</div>
@endsection
