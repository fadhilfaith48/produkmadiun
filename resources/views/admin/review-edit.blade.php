@extends('layouts.app')

@section('title', 'Edit Ulasan — Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">✏️ Edit Ulasan</h3>
            <small class="text-muted">ID Ulasan: {{ $review->id }}</small>
        </div>
        <a href="{{ route('admin.reviews') }}" class="btn btn-outline-secondary btn-sm">← Kembali ke Daftar Ulasan</a>
    </div>

    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="rating" class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                    <select name="rating" id="rating" class="form-select" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                {{ $i }} <i class="bi bi-star-fill text-warning"></i>
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="comment" class="form-label fw-bold">Komentar</label>
                    <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Tulis komentar baru...">{{ $review->comment }}</textarea>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
                <a href="{{ route('admin.reviews') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
