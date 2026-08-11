@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Tambah Produk Baru</h3>
        <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:12px">
        <div class="card-body p-4">
            <form action="{{ route('umkm.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" min="0" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                               value="{{ old('stock', 0) }}" min="0" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control"
                               value="{{ old('unit', 'pcs') }}" placeholder="pcs / kg / lusin" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Berat (gram)</label>
                        <input type="number" name="weight" class="form-control"
                               value="{{ old('weight', 0) }}" min="0">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi Produk <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Foto Utama</label>
                        <input type="file" name="image" class="form-control" accept="image/*"
                               onchange="previewImg(this,'prev-main')">
                        <img id="prev-main" class="mt-2 rounded d-none" style="height:80px;object-fit:cover">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Foto Tambahan (galeri)</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Bisa pilih beberapa foto sekaligus</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn text-white fw-semibold px-5"
                            style="background:#2D6A4F;border-radius:8px;padding:10px">
                        Simpan Produk
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