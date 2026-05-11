@extends('layouts.app')
@section('title', 'Kelola Toko — Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">🏪 Kelola Toko UMKM</h3>
            <small class="text-muted">Verifikasi dan kelola toko yang terdaftar</small>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Filter tab --}}
    <div class="d-flex gap-2 mb-3">
        <span class="badge fs-6 px-3 py-2" style="background:#d8f3dc;color:#2d6a4f">
            Semua: {{ $stores->total() }}
        </span>
        <span class="badge fs-6 px-3 py-2 bg-warning text-dark">
            Belum Verifikasi: {{ $stores->where('is_verified', false)->count() }}
        </span>
        <span class="badge fs-6 px-3 py-2 bg-success">
            Terverifikasi: {{ $stores->where('is_verified', true)->count() }}
        </span>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Toko</th>
                            <th>Pemilik</th>
                            <th>Lokasi</th>
                            <th>Produk</th>
                            <th>Daftar</th>
                            <th>Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/'.$store->logo) }}"
                                             style="width:40px;height:40px;object-fit:cover;border-radius:8px">
                                    @else
                                        <div style="width:40px;height:40px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center">🏪</div>
                                    @endif
                                    <div>
                                        <p class="mb-0 fw-semibold">{{ $store->store_name }}</p>
                                        <small class="text-muted">{{ $store->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0">{{ $store->user->name ?? '-' }}</p>
                                <small class="text-muted">{{ $store->user->email ?? '-' }}</small>
                            </td>
                            <td>
                                <small>{{ $store->district ?? $store->village ?? $store->address ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $store->products()->count() }} produk</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $store->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                @if($store->is_verified)
                                    <span class="badge bg-success">✅ Terverifikasi</span>
                                @else
                                    <span class="badge bg-warning text-dark">⏳ Menunggu</span>
                                @endif
                            </td>
                            <td>
                                @if(!$store->is_verified)
                                    {{-- Tombol Verifikasi --}}
                                    <form action="{{ route('admin.stores.verify', $store->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button class="btn btn-sm btn-success"
                                                onclick="return confirm('Verifikasi toko {{ $store->store_name }}?')">
                                            ✅ Verifikasi
                                        </button>
                                    </form>
                                @else
                                    {{-- Tombol Batalkan --}}
                                    <form action="{{ route('admin.stores.unverify', $store->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Batalkan verifikasi toko ini?')">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('stores.show', $store->slug) }}"
                                   class="btn btn-sm btn-outline-secondary" target="_blank">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada toko terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $stores->links() }}</div>
</div>
@endsection
