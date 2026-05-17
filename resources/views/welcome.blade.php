@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; background: linear-gradient(135deg, #f0fdf4 0%, #dbeafe 50%, #fef3c7 100%); position: relative; }
        body::before { content: ''; position: fixed; inset: 0; background: url('https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1920&q=80') center/cover no-repeat; opacity: 0.15; z-index: 0; }
        .welcome-wrap { position: relative; z-index: 1; display: flex; align-items: center; gap: 48px; max-width: 900px; width: 100%; }
        .welcome-hero { flex: 1; display: none; }
        @media (min-width: 768px) { .welcome-hero { display: block; } }
        .welcome-hero h1 { font-size: 2.2rem; font-weight: 800; color: #166534; line-height: 1.2; margin-bottom: 16px; }
        .welcome-hero p { font-size: 1rem; color: #475569; line-height: 1.6; margin-bottom: 12px; }
        .welcome-hero .icon-row { display: flex; gap: 16px; margin-top: 24px; }
        .welcome-hero .icon-row span { width: 48px; height: 48px; border-radius: 14px; background: rgba(255,255,255,0.7); display: grid; place-items: center; font-size: 1.3rem; }
        .welcome-card { background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); border-radius: 24px; padding: 40px 36px; box-shadow: 0 20px 48px rgba(15,23,42,0.1); text-align: center; max-width: 380px; width: 100%; border: 1px solid rgba(255,255,255,0.5); }
        .logo { width: 64px; height: 64px; border-radius: 20px; background: linear-gradient(135deg, #bbf7d0, #86efac); display: grid; place-items: center; margin: 0 auto 20px; box-shadow: 0 4px 12px rgba(34,197,94,0.2); }
        .logo span { width: 28px; height: 28px; border-radius: 999px; background: #16a34a; display: block; }
        .welcome-card h2 { font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .welcome-card .sub { font-size: 0.82rem; color: #64748b; margin-bottom: 24px; line-height: 1.5; }
        .actions { display: flex; flex-direction: column; gap: 10px; }
        .btn { display: block; padding: 12px 20px; border-radius: 14px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: #4f9b4f; color: #fff; }
        .btn-primary:hover { background: #3d8a3d; }
        .btn-outline { border: 1px solid #e2e8f0; color: #475569; background: #fff; }
        .btn-outline:hover { background: #f8fafc; }
        .footer-text { margin-top: 16px; font-size: 0.72rem; color: #94a3b8; }
        .footer-text a { color: #4f9b4f; text-decoration: none; }
    </style>
@endpush

<x-guest-layout>
    <div class="welcome-wrap">
        <div class="welcome-hero">
            <h1>Pendampingan Spiritual untuk Pasien Paliatif</h1>
            <p>Temukan ketenangan, makna, dan harapan setiap hari melalui layanan spiritual dan psikososial yang terintegrasi.</p>
            <div class="icon-row">
                <span>🙏</span>
                <span>☮️</span>
                <span>💚</span>
                <span>✨</span>
            </div>
        </div>
        <div class="welcome-card">
            <div class="logo"><span></span></div>
            <h2>Terapi Rohani</h2>
            <p class="sub">Pasien Paliatif — Dampingan spiritual untuk menemukan kedamaian.</p>
            <div class="actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Masuk ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline">Daftar Akun Baru</a>
                @endauth
            </div>
            <p class="footer-text">
                Foto oleh <a href="https://unsplash.com/@anniespratt" target="_blank">Annie Spratt</a> di Unsplash
            </p>
        </div>
    </div>
</x-guest-layout>
