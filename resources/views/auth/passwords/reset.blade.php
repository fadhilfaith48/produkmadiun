@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius:16px">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div style="font-size:2.5rem">🔑</div>
                        <h4 class="fw-bold mt-2">Reset Password</h4>
                        <p class="text-muted small">Masukkan password baru kamu di bawah ini.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ $email ?? old('email') }}"
                                   required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password" id="pw-new"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter" required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePw('pw-new', 'icon-new')">
                                    <i id="icon-new" class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="pw-conf"
                                       class="form-control"
                                       placeholder="Ulangi password baru" required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePw('pw-conf', 'icon-conf')">
                                    <i id="icon-conf" class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 fw-semibold text-white"
                                style="background:#2D6A4F;border-radius:8px;padding:11px">
                            <i class="bi bi-check-circle me-2"></i>Reset Password
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-muted small">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endsection