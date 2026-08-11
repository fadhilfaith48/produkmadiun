@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Edit Produk</h3>
        <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body p-4">
                    <form action="{{ route('umkm.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $product->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Pilih kategori...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->icon }} {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $product->price) }}" min="0" required>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', $product->stock) }}" min="0" required>
                                @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="unit" class="form-control"
                                       value="{{ old('unit', $product->unit) }}" placeholder="pcs / kg / lusin" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Berat (gram)</label>
                                <input type="number" name="weight" class="form-control"
                                       value="{{ old('weight', $product->weight) }}" min="0">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi Produk <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                          rows="4" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Foto Utama</label>
                                <input type="file" name="image" class="form-control" accept="image/*"
                                       onchange="previewImg(this,'prev-main')">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         id="prev-main" class="mt-2 rounded" style="height:80px;object-fit:cover">
                                @else
                                    <img id="prev-main" class="mt-2 rounded d-none" style="height:80px;object-fit:cover">
                                @endif
                            </div>

                            <div class="col-md-6">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                           value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Produk Aktif (tampil di publik)</label>
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

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius:12px">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">🖼️ Galeri Foto</h6>

                    <form action="{{ route('umkm.products.upload-image', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="image" class="form-control mb-2" accept="image/*" required>
                        <button class="btn btn-sm btn-success w-100">Tambah Foto</button>
                    </form>

                    @if($product->images->count() > 0)
                        <div class="d-flex flex-column gap-2 mt-3">
                            @foreach($product->images as $img)
                            <div class="d-flex align-items-center gap-2 border rounded p-1">
                                <img src="{{ asset('storage/' . $img->image) }}"
                                     style="width:52px;height:52px;object-fit:cover;border-radius:6px">
                                <form action="{{ route('umkm.products.delete-image', $img->id) }}" method="POST" class="ms-auto"
                                      onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mt-3 mb-0">Belum ada foto galeri.</p>
                    @endif
                </div>
            </div>
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