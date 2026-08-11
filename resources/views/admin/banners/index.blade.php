@extends('layouts.app')
@section('title', 'Kelola Banner — Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">🎯 Kelola Banner</h3>
            <small class="text-muted">Banner yang tampil di beranda</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">← Dashboard</a>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-sm text-white" style="background:#2D6A4F">
                + Tambah Banner
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-3">
        @forelse($banners as $banner)
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:12px">
                <img src="{{ asset('storage/' . $banner->image) }}"
                     class="card-img-top" style="height:160px;object-fit:cover;border-radius:12px 12px 0 0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold mb-0">{{ $banner->title }}</h6>
                            <small class="text-muted">Urutan: {{ $banner->order }}</small>
                        </div>
                        @if($banner->is_active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </div>
                    @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="small text-decoration-none">
                            {{ $banner->link }}
                        </a>
                    @endif
                </div>
                <div class="card-footer bg-white d-flex gap-2">
                    <a href="{{ route('admin.banners.edit', $banner->id) }}"
                       class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                          onsubmit="return confirm('Hapus banner ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5 text-muted">
                    Belum ada banner. <a href="{{ route('admin.banners.create') }}">Tambah banner pertama</a>.
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-3">{{ $banners->links() }}</div>
</div>
@endsection
