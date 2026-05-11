{{-- =====================================================
FILE: resources/views/auth/register.blade.php
Halaman Register — Full Page Modern (sama dengan Login)
===================================================== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — ProdukMadiun</title>
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
            width: 560px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 52px;
            background: #fff;
            position: relative;
            overflow-y: auto;
        }

        .right-inner { width: 100%; max-width: 420px; }

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
            margin-bottom: 32px;
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
        .field-group { margin-bottom: 18px; }

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

        /* select */
        .field-select {
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
            cursor: pointer;
        }

        .field-select:focus {
            border-color: var(--mid);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(64,145,108,.12);
        }

        .field-select.is-invalid { border-color: var(--red); }

        /* phone group */
        .phone-wrap {
            display: flex;
            gap: 0;
        }

        .phone-prefix {
            height: 46px;
            padding: 0 14px;
            background: var(--pale);
            border: 1.5px solid var(--border);
            border-right: none;
            border-radius: 10px 0 0 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .9rem;
            font-weight: 600;
            color: var(--green);
            display: flex;
            align-items: center;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .phone-input {
            flex: 1;
            height: 46px;
            border: 1.5px solid var(--border);
            border-radius: 0 10px 10px 0;
            background: var(--cream);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .9rem;
            color: var(--text);
            padding: 0 14px;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
            -webkit-appearance: none;
        }

        .phone-input:focus {
            border-color: var(--mid);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(64,145,108,.12);
        }

        .field-hint {
            font-size: .76rem;
            color: var(--muted);
            margin-top: 5px;
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

        /* ── SUBMIT BTN ─────────────────── */
        .btn-register {
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
            margin-top: 24px;
            margin-bottom: 24px;
        }

        .btn-register:hover {
            background: var(--forest);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(45,106,79,.35);
        }

        .btn-register:active { transform: translateY(0); }

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

        /* ── LOGIN LINK ─────────────────── */
        .login-prompt {
            text-align: center;
            font-size: .85rem;
            color: var(--muted);
        }
        .login-prompt a {
            color: var(--green);
            font-weight: 700;
            text-decoration: none;
        }
        .login-prompt a:hover { text-decoration: underline; }

        /* ── RESPONSIVE ─────────────────── */
        @media (max-width: 960px) {
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
            <h2>Bergabung bersama<br><em>Sobat UMKM</em> Madiun</h2>
            <p>Daftarkan diri Anda dan mulai perjalanan bersama ribuan pelaku UMKM Kabupaten Madiun.</p>
            <ul class="left-features">
                <li>
                    <span class="feat-dot"><i class="bi bi-shop"></i></span>
                    Buka toko UMKM Anda secara gratis
                </li>
                <li>
                    <span class="feat-dot"><i class="bi bi-bag-heart"></i></span>
                    Belanja produk lokal berkualitas
                </li>
                <li>
                    <span class="feat-dot"><i class="bi bi-graph-up-arrow"></i></span>
                    Pantau penjualan & kembangkan bisnis
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

            <h1 class="page-title">Buat Akun Baru</h1>
            <p class="page-sub">Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--green);font-weight:600;text-decoration:none;">Masuk di sini</a></p>

            {{-- Errors --}}
            @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-circle" style="margin-top:1px;flex-shrink:0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Nama --}}
                <div class="field-group">
                    <label class="field-label" for="name">Nama Lengkap</label>
                    <div class="field-wrap">
                        <i class="bi bi-person fi"></i>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="field-input @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap Anda"
                            required
                            autocomplete="name"
                            autofocus
                        >
                    </div>
                    @error('name')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

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
                        >
                    </div>
                    @error('email')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- No. WhatsApp --}}
                <div class="field-group">
                    <label class="field-label" for="phone">No. WhatsApp</label>
                    <div class="phone-wrap">
                        <span class="phone-prefix">+62</span>
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            class="phone-input @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}"
                            placeholder="81234567890"
                            autocomplete="tel"
                        >
                    </div>
                    @error('phone')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                    <div class="field-hint">Opsional, untuk konfirmasi pesanan</div>
                </div>

                {{-- Jadi Apa? --}}
                <div class="field-group">
                    <label class="field-label" for="role">Daftar Sebagai</label>
                    <div class="field-wrap">
                        <i class="bi bi-people fi"></i>
                        <select
                            id="role"
                            name="role"
                            class="field-select @error('role') is-invalid @enderror"
                            required
                        >
                            <option value="">Pilih peran Anda...</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>🛒 Pembeli</option>
                            <option value="umkm"     {{ old('role') == 'umkm'     ? 'selected' : '' }}>🏪 Penjual UMKM</option>
                        </select>
                    </div>
                    @error('role')
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
                            placeholder="Minimal 8 karakter"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="pwd-toggle" id="togglePwd" aria-label="Tampilkan kata sandi">
                            <i class="bi bi-eye" id="eyeIconPwd"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="field-group">
                    <label class="field-label" for="password-confirm">Konfirmasi Kata Sandi</label>
                    <div class="field-wrap">
                        <i class="bi bi-lock-fill fi"></i>
                        <input
                            id="password-confirm"
                            name="password_confirmation"
                            type="password"
                            class="field-input"
                            placeholder="Ulangi kata sandi"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="pwd-toggle" id="toggleConfirm" aria-label="Tampilkan konfirmasi kata sandi">
                            <i class="bi bi-eye" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-register">
                    <i class="bi bi-person-plus"></i>
                    Daftar Sekarang
                </button>

            </form>

            <div class="divider"><span>atau</span></div>

            <div class="login-prompt">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk ke akun Anda →</a>
            </div>

        </div>
    </div>

    <script>
        // Toggle password
        const togglePwd = document.getElementById('togglePwd');
        const pwdInput  = document.getElementById('password');
        const eyePwd    = document.getElementById('eyeIconPwd');
        togglePwd.addEventListener('click', () => {
            const show = pwdInput.type === 'password';
            pwdInput.type   = show ? 'text' : 'password';
            eyePwd.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });

        // Toggle confirm password
        const toggleConfirm = document.getElementById('toggleConfirm');
        const confirmInput  = document.getElementById('password-confirm');
        const eyeConfirm    = document.getElementById('eyeIconConfirm');
        toggleConfirm.addEventListener('click', () => {
            const show = confirmInput.type === 'password';
            confirmInput.type   = show ? 'text' : 'password';
            eyeConfirm.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>
</body>
</html>
