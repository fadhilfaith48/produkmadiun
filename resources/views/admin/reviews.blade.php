@extends('layouts.app')
@section('title', 'Kelola Ulasan — Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">💬 Kelola Ulasan</h3>
            <small class="text-muted">Setujui atau sembunyikan ulasan pelanggan</small>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex gap-2 mb-3">
        <span class="badge fs-6 px-3 py-2 bg-light text-dark">
            Semua: {{ $reviews->total() }}
        </span>
        <span class="badge fs-6 px-3 py-2 bg-warning text-dark">
            Menunggu: {{ $reviews->where('is_approved', false)->count() }}
        </span>
        <span class="badge fs-6 px-3 py-2 bg-success">
            Disetujui: {{ $reviews->where('is_approved', true)->count() }}
        </span>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Pengulas</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>
                                <a href="{{ route('products.show', $review->product->slug ?? '') }}"
                                   class="text-decoration-none" target="_blank">
                                    {{ $review->product->name ?? 'Produk dihapus' }}
                                </a>
                            </td>
                            <td>
                                <p class="mb-0 fw-semibold">{{ $review->reviewer_name ?: ($review->user->name ?? 'Anonim') }}</p>
                                @if($review->user)
                                    <small class="text-muted">{{ $review->user->email }}</small>
                                @endif
                            </td>
                            <td>
                                <div style="color:#f4a228">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} small"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($review->comment, 60) ?: '—' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                            </td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge bg-success">✅ Disetujui</span>
                                @else
                                    <span class="badge bg-warning text-dark">⏳ Menunggu</span>
                                @endif
                            </td>
                            <td>
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.reviews.approve', $review->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button class="btn btn-sm btn-success">✅ Setujui</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.reviews.reject', $review->id) }}"
                                          method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <button class="btn btn-sm btn-outline-danger">Sembunyikan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada ulasan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">{{ $reviews->links() }}</div>
</div>
@endsection
