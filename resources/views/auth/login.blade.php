{{-- =====================================================
FILE: resources/views/auth/login.blade.php
Halaman Login — Full Page Modern
===================================================== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — ProdukMadiun</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,700;1,9..144,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --forest:  #1a3d2b;
            --green:   #2d6a4f;
            --mid:     #40916c;
            --light:   #74c69d;
            --pale:    #d8f3dc;
            --gold:    #f4a228;
            --gold-dk: #c07d10;
            --cream:   #faf8f4;
            --text:    #1c2b22;
            --muted:   #5a7368;
            --border:  #dde8e2;
            --red:     #d64545;
        }

        html, body { height: 100%; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }

        /* ── LEFT PANEL ─────────────────── */
        .left-panel {
            flex: 1;
            background: var(--forest);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 52px;
            min-height: 100vh;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 110% 50%, rgba(64,145,108,.4) 0%, transparent 65%),
                radial-gradient(ellipse 60% 70% at -10% 80%, rgba(116,198,157,.18) 0%, transparent 60%);
            pointer-events: none;
        }

        .left-orb {
            position: absolute;
            right: -90px;
            bottom: -90px;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            border: 55px solid rgba(116,198,157,.1);
            box-shadow: 0 0 0 28px rgba(116,198,157,.05);
        }

        .left-dots {
            position: absolute;
            top: 40px;
            right: 40px;
            display: grid;
            grid-template-columns: repeat(5, 6px);
            gap: 8px;
        }
        .left-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(116,198,157,.25);
            display: block;
        }

        .left-panel .brand {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .left-panel .brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }
        .left-panel .brand-name {
            font-family: 'Fraunces', serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.01em;
        }

        .left-content {
            position: relative;
            z-index: 2;
        }

        .left-content h2 {
            font-family: 'Fraunces', serif;
            font-size: 2.4rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .left-content h2 em {
            font-style: italic;
            color: var(--gold);
        }

        .left-content p {
            color: rgba(255,255,255,.6);
            font-size: .95rem;
            line-height: 1.75;
            max-width: 320px;
            margin-bottom: 36px;
        }

        .left-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .left-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,.78);
            font-size: .875rem;
            font-weight: 500;
        }

        .feat-dot {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(116,198,157,.2);
            border: 1px solid rgba(116,198,157,.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .75rem;
            color: var(--light);
        }

        .left-footer {
            position: relative;
            z-index: 2;
            color: rgba(255,255,255,.35);
            font-size: .75rem;
        }

        /* ── RIGHT PANEL ────────────────── */
        .right-panel {
            width: 520px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 52px;
            background: #fff;
            position: relative;
        }

        .right-inner { width: 100%; max-width: 380px; }

        .right-inner .page-title {
            font-family: 'Fraunces', serif;
            font-size: 1.85rem;
            font-weight: 700;
            color: var(--forest);
            margin-bottom: 6px;
        }

        .right-inner .page-sub {
            color: var(--muted);
            font-size: .875rem;
            margin-bottom: 36px;
        }

        /* ── ALERT ──────────────────────── */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            color: var(--red);
            font-size: .85rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        /* ── FORM FIELDS ────────────────── */
        .field-group { margin-bottom: 20px; }

        .field-label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 7px;
            letter-spacing: .02em;
        }

        .field-wrap {
            position: relative;
        }

        .field-wrap .fi {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: .9rem;
            pointer-events: none;
            z-index: 1;
        }

        .field-input {
            width: 100%;
            height: 46px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: var(--cream);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .9rem;
            color: var(--text);
            padding: 0 14px 0 40px;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            -webkit-appearance: none;
        }

        .field-input:focus {
            border-color: var(--mid);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(64,145,108,.12);
        }

        .field-input.is-invalid {
            border-color: var(--red);
        }

        .field-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(214,69,69,.12);
        }

        .field-error {
            color: var(--red);
            font-size: .78rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* password toggle */
        .pwd-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            font-size: .95rem;
            padding: 4px;
            line-height: 1;
            transition: color .15s;
        }
        .pwd-toggle:hover { color: var(--green); }

        /* ── REMEMBER + FORGOT ──────────── */
        .form-extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-wrap input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--green);
            cursor: pointer;
        }

        .remember-wrap span {
            font-size: .82rem;
            color: var(--muted);
            font-weight: 500;
        }

        .forgot-link {
            font-size: .82rem;
            font-weight: 600;
            color: var(--green);
            text-decoration: none;
            transition: color .15s;
        }
        .forgot-link:hover { color: var(--forest); text-decoration: underline; }

        /* ── SUBMIT BTN ─────────────────── */
        .btn-login {
            width: 100%;
            height: 48px;
            background: var(--green);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(45,106,79,.28);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .btn-login:hover {
            background: var(--forest);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(45,106,79,.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* ── DIVIDER ────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .divider span { font-size: .75rem; color: #b0bfb8; font-weight: 500; }

        /* ── REGISTER LINK ──────────────── */
        .register-prompt {
            text-align: center;
            font-size: .85rem;
            color: var(--muted);
        }
        .register-prompt a {
            color: var(--green);
            font-weight: 700;
            text-decoration: none;
        }
        .register-prompt a:hover { text-decoration: underline; }

        /* ── RESPONSIVE ─────────────────── */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel {
                width: 100%;
                min-height: 100vh;
                padding: 40px 28px;
            }
        }
    </style>
</head>
<body>

    {{-- LEFT PANEL --}}
    <div class="left-panel">
        <div class="left-dots">
            @for($i = 0; $i < 15; $i++)
                <span></span>
            @endfor
        </div>
        <div class="left-orb"></div>

        <a href="{{ url('/') }}" class="brand">
            <div class="brand-icon">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo" style="width:26px;height:26px">
            </div>
            <span class="brand-name">ProdukMadiun</span>
        </a>

        <div class="left-content">
            <h2>Selamat datang<br>kembali, <em>Sobat UMKM</em></h2>
            <p>Masuk ke akun Anda untuk mengelola toko, memantau pesanan, dan mengembangkan bisnis lokal bersama kami.</p>
            <ul class="left-features">
                <li>
                    <span class="feat-dot"><i class="bi bi-shop"></i></span>
                    Kelola toko UMKM Anda dengan mudah
                </li>
                <li>
                    <span class="feat-dot"><i class="bi bi-box-seam"></i></span>
                    Pantau pesanan masuk secara real-time
                </li>
                <li>
                    <span class="feat-dot"><i class="bi bi-graph-up-arrow"></i></span>
                    Lihat laporan penjualan & statistik toko
                </li>
                <li>
                    <span class="feat-dot"><i class="bi bi-patch-check"></i></span>
                    Raih status terverifikasi dari Pemkab Madiun
                </li>
            </ul>
        </div>

        <div class="left-footer">
            &copy; {{ date('Y') }} ProdukMadiun &mdash; Platform UMKM Kabupaten Madiun
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="right-panel">
        <div class="right-inner">

            {{-- Mobile brand --}}
            <div style="display:none" class="d-block d-md-none mb-4">
                <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:8px;text-decoration:none;">
                    <div style="width:32px;height:32px;border-radius:8px;background:var(--green);display:flex;align-items:center;justify-content:center;font-size:.9rem;">🌿</div>
                    <span style="font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--forest);">ProdukMadiun</span>
                </a>
            </div>

            <h1 class="page-title">Masuk ke Akun</h1>
            <p class="page-sub">Belum punya akun? <a href="{{ route('register') }}" style="color:var(--green);font-weight:600;text-decoration:none;">Daftar gratis</a></p>

            {{-- Session errors --}}
            @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle" style="margin-top:1px;flex-shrink:0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            @if (session('status'))
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:10px;padding:12px 16px;margin-bottom:22px;color:#166534;font-size:.85rem;display:flex;align-items:center;gap:8px;">
                <i class="bi bi-check-circle"></i>
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <div class="field-wrap">
                        <i class="bi bi-envelope fi"></i>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="field-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                            autocomplete="email"
                            autofocus
                        >
                    </div>
                    @error('email')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field-group">
                    <label class="field-label" for="password">Kata Sandi</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock fi"></i>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="field-input @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="pwd-toggle" id="togglePwd" aria-label="Tampilkan kata sandi">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="form-extras">
                    <label class="remember-wrap">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk Sekarang
                </button>

            </form>

            <div class="divider"><span>atau</span></div>

            <div class="register-prompt">
                Belum punya toko? <a href="{{ route('register') }}">Daftarkan UMKM Anda →</a>
            </div>

        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePwd');
        const pwd    = document.getElementById('password');
        const eye    = document.getElementById('eyeIcon');
        toggle.addEventListener('click', () => {
            const show = pwd.type === 'password';
            pwd.type   = show ? 'text' : 'password';
            eye.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>
</body>
</html>