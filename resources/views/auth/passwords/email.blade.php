{{-- =====================================================
FILE: resources/views/auth/verify.blade.php
Halaman Verifikasi Email — Full Page Modern
===================================================== --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — ProdukMadiun</title>
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
            --cream:   #faf8f4;
            --text:    #1c2b22;
            --muted:   #5a7368;
            --border:  #dde8e2;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 32px 20px;
        }

        /* ── BRAND ─────────────────────── */
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 48px;
        }
        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .brand-name {
            font-family: 'Fraunces', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--forest);
        }

        /* ── CARD ──────────────────────── */
        .verify-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid var(--border);
            padding: 52px 48px;
            max-width: 460px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 40px rgba(45,106,79,.07);
        }

        /* ── ENVELOPE ICON ─────────────── */
        .email-icon-wrap {
            width: 80px;
            height: 80px;
            border-radius: 22px;
            background: var(--pale);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            position: relative;
        }
        .email-icon-wrap i {
            font-size: 2.1rem;
            color: var(--green);
        }
        /* pulse ring */
        .email-icon-wrap::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 30px;
            border: 2px solid var(--pale);
            animation: pulse-ring 2.4s ease-out infinite;
        }
        @keyframes pulse-ring {
            0%   { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(1.18); }
        }

        /* ── TEXT ──────────────────────── */
        .verify-card h1 {
            font-family: 'Fraunces', serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: var(--forest);
            margin-bottom: 12px;
            line-height: 1.25;
        }

        .verify-card .desc {
            font-size: .9rem;
            color: var(--muted);
            line-height: 1.75;
            margin-bottom: 32px;
        }

        .verify-card .desc strong {
            color: var(--text);
            font-weight: 600;
        }

        /* ── STEPS ─────────────────────── */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 36px;
            text-align: left;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--cream);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 16px;
        }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: var(--green);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-text {
            font-size: .82rem;
            color: var(--muted);
            font-weight: 500;
            line-height: 1.4;
        }

        /* ── SUCCESS ALERT ─────────────── */
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 28px;
            color: #166534;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }
        .alert-success i { font-size: 1.1rem; flex-shrink: 0; }

        /* ── RESEND BTN ────────────────── */
        .btn-resend {
            width: 100%;
            height: 48px;
            background: var(--green);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(45,106,79,.25);
            margin-bottom: 20px;
        }
        .btn-resend:hover {
            background: var(--forest);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(45,106,79,.32);
        }
        .btn-resend:disabled {
            opacity: .55;
            cursor: not-allowed;
            transform: none;
        }

        /* ── COUNTDOWN ─────────────────── */
        .resend-note {
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: 28px;
        }
        #countdown { font-weight: 600; color: var(--green); }

        /* ── DIVIDER ───────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .divider span { font-size: .72rem; color: #b0bfb8; font-weight: 500; }

        /* ── BACK LINK ─────────────────── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            transition: color .15s;
        }
        .back-link:hover { color: var(--green); }

        /* ── FOOTER ────────────────────── */
        .page-footer {
            margin-top: 36px;
            font-size: .75rem;
            color: #b0bfb8;
        }
    </style>
</head>
<body>

    <a href="{{ url('/') }}" class="brand">
        <div class="brand-icon">🌿</div>
        <span class="brand-name">ProdukMadiun</span>
    </a>

    <div class="verify-card">

        {{-- Success alert --}}
        @if (session('resent'))
        <div class="alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>Link verifikasi baru sudah dikirim ke email Anda. Mohon cek inbox atau folder spam.</span>
        </div>
        @endif

        {{-- Envelope icon --}}
        <div class="email-icon-wrap">
            <i class="bi bi-envelope-at"></i>
        </div>

        <h1>Verifikasi Email Anda</h1>
        <p class="desc">
            Kami telah mengirim tautan verifikasi ke <strong>{{ Auth::user()?->email ?? ''  }}</strong>.
            Klik tautan tersebut untuk mengaktifkan akun Anda.
        </p>

        {{-- Steps --}}
        <div class="steps">
            <div class="step-item">
                <span class="step-num">1</span>
                <span class="step-text">Buka aplikasi email Anda</span>
            </div>
            <div class="step-item">
                <span class="step-num">2</span>
                <span class="step-text">Cari email dari <strong>ProdukMadiun</strong>, termasuk di folder <em>Spam</em></span>
            </div>
            <div class="step-item">
                <span class="step-num">3</span>
                <span class="step-text">Klik tombol <strong>"Verifikasi Email"</strong> di dalam email</span>
            </div>
        </div>

        {{-- Resend form --}}
        <form method="POST" action="{{ route('verification.resend') }}" id="resendForm">
            @csrf
            <button type="submit" class="btn-resend" id="resendBtn">
                <i class="bi bi-send"></i>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <p class="resend-note" id="resendNote" style="display:none">
            Kirim ulang tersedia dalam <span id="countdown">60</span> detik
        </p>

        <div class="divider"><span>atau</span></div>

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="back-link">
            <i class="bi bi-arrow-left"></i>
            Kembali / Ganti akun
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
            @csrf
        </form>

    </div>

    <p class="page-footer">&copy; {{ date('Y') }} ProdukMadiun &mdash; Platform UMKM Kabupaten Madiun</p>

    <script>
        const btn  = document.getElementById('resendBtn');
        const note = document.getElementById('resendNote');
        const cd   = document.getElementById('countdown');

        @if (session('resent'))
        startCountdown();
        @endif

        document.getElementById('resendForm').addEventListener('submit', () => {
            startCountdown();
        });

        function startCountdown() {
            btn.disabled = true;
            note.style.display = 'block';
            let secs = 60;
            cd.textContent = secs;
            const t = setInterval(() => {
                secs--;
                cd.textContent = secs;
                if (secs <= 0) {
                    clearInterval(t);
                    btn.disabled = false;
                    note.style.display = 'none';
                }
            }, 1000);
        }
    </script>
</body>
</html>