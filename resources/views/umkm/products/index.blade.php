@extends('layouts.app')
@section('title', 'Kelola Produk')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">📦 Kelola Produk</h3>
            <small class="text-muted">{{ $store->store_name }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('umkm.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
            <a href="{{ route('umkm.products.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($products->count() > 0)
    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">Foto</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         style="width:48px;height:48px;object-fit:cover;border-radius:8px">
                                @else
                                    <div style="width:48px;height:48px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.2rem">📦</div>
                                @endif
                            </td>
                            <td>
                                <p class="mb-0 fw-semibold">{{ $product->name }}</p>
                                <small class="text-muted">{{ $product->unit }}</small>
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $product->category->name ?? '-' }}</span></td>
                            <td><strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong></td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('umkm.products.edit', $product->id) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('umkm.products.destroy', $product->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>

    @else
    <div class="card border-0 shadow-sm text-center py-5" style="border-radius:12px">
        <div style="font-size:3rem">📦</div>
        <h5 class="mt-3 fw-bold">Belum ada produk</h5>
        <p class="text-muted">Mulai tambahkan produk pertama toko Anda</p>
        <div>
            <a href="{{ route('umkm.products.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Tambah Produk Pertama
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
