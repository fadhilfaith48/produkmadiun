@extends('layouts.app')
@section('title', 'Profil Toko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">🏪 Profil Toko UMKM</h3>
        <a href="{{ route('umkm.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            ← Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-4">
            <form action="{{ route('umkm.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Nama Toko --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Nama Toko <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="store_name" class="form-control @error('store_name') is-invalid @enderror"
                               value="{{ $store->store_name ?? old('store_name') }}" required>
                        @error('store_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- No. WhatsApp --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            No. WhatsApp <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                               value="{{ $store->whatsapp ?? old('whatsapp') }}"
                               placeholder="08xxxxxxxxxx" required>
                        @error('whatsapp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- No. Telepon --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">No. Telepon</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ $store->phone ?? old('phone') }}"
                               placeholder="08xxxxxxxxxx">
                    </div>

                    {{-- Kecamatan --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kecamatan</label>
                        <input type="text" name="district" class="form-control"
                               value="{{ $store->district ?? old('district') }}"
                               placeholder="Contoh: Mejayan">
                    </div>

                    {{-- Desa --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Desa/Kelurahan</label>
                        <input type="text" name="village" class="form-control"
                               value="{{ $store->village ?? old('village') }}"
                               placeholder="Contoh: Krajan">
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Alamat Lengkap <span class="text-danger">*</span>
                        </label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Jl. ... No. ... Desa ... Kecamatan ..."
                                  required>{{ $store->address ?? old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Toko</label>
                        <textarea name="description" rows="3" class="form-control"
                                  placeholder="Ceritakan tentang toko kamu...">{{ $store->description ?? old('description') }}</textarea>
                    </div>

                    {{-- Logo --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Logo Toko</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($store && $store->logo)
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/'.$store->logo) }}"
                                     class="rounded" style="height:50px;width:50px;object-fit:cover">
                                <small class="text-muted">Logo saat ini</small>
                            </div>
                        @endif
                    </div>

                    {{-- Banner --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Banner Toko</label>
                        <input type="file" name="banner" class="form-control" accept="image/*">
                        @if($store && $store->banner)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$store->banner) }}"
                                     class="rounded" style="height:50px;object-fit:cover;width:100%">
                                <small class="text-muted">Banner saat ini</small>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn fw-semibold text-white px-4"
                            style="background:#2D6A4F;border-radius:8px">
                        💾 Simpan Profil Toko
                    </button>
                    <a href="{{ route('umkm.dashboard') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- Info toko saat ini --}}
    @if($store)
    <div class="card border-0 shadow-sm mt-4" style="border-radius:12px;background:#E1F5EE">
        <div class="card-body p-3">
            <p class="fw-semibold mb-1" style="color:#2D6A4F">Info Toko Aktif</p>
            <p class="mb-0 small text-muted">
                Slug: <code>{{ $store->slug }}</code> |
                Status: 
                @if($store->is_verified)
                    <span class="badge bg-success">Terverifikasi</span>
                @else
                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                @endif
            </p>
        </div>
    </div>
    @endif
</div>
@endsection