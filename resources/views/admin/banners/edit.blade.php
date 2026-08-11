@extends('layouts.app')
@section('title', 'Edit Banner — Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Edit Banner</h3>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-4">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $banner->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="order" class="form-control"
                               value="{{ old('order', $banner->order) }}" min="0">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Gambar</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                               accept="image/*" onchange="previewImg(this,'prev')">
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <img src="{{ asset('storage/' . $banner->image) }}" id="prev"
                             class="mt-2 rounded" style="height:100px;object-fit:cover">
                        <small class="text-muted d-block">Kosongkan jika tidak mengganti gambar.</small>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Tautan (opsional)</label>
                        <input type="url" name="link" class="form-control @error('link') is-invalid @enderror"
                               value="{{ old('link', $banner->link) }}" placeholder="https://...">
                        @error('link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Aktif (tampil di beranda)</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn text-white fw-semibold px-5"
                            style="background:#2D6A4F;border-radius:8px;padding:10px">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImg(input, id) {
    const el = document.getElementById(id);
    if (input.files && input.files[0]) {
        el.src = URL.createObjectURL(input.files[0]);
        el.classList.remove('d-none');
    }
}
</script>
@endsection