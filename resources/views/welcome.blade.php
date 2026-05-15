@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Manrope', sans-serif; background: #f4f6fb; color: #0f172a; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }
        .welcome-card { background: #fff; border-radius: 24px; padding: 48px 40px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); text-align: center; max-width: 420px; width: 100%; }
        .logo { width: 64px; height: 64px; border-radius: 20px; background: #dff3df; display: grid; place-items: center; margin: 0 auto 20px; }
        .logo span { width: 32px; height: 32px; border-radius: 999px; background: #63b96b; display: block; }
        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 8px; }
        p { font-size: 0.9rem; color: #64748b; margin-bottom: 28px; line-height: 1.5; }
        .actions { display: flex; flex-direction: column; gap: 10px; }
        .btn { display: block; padding: 12px 20px; border-radius: 14px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
        .btn-primary { background: #4f9b4f; color: #fff; }
        .btn-primary:hover { background: #3d8a3d; }
        .btn-outline { border: 1px solid #e2e8f0; color: #475569; }
        .btn-outline:hover { background: #f8fafc; }
    </style>
@endpush

<x-guest-layout>
    <div class="welcome-card">
        <div class="logo"><span></span></div>
        <h1>Terapi Rohani</h1>
        <p>Pendampingan spiritual dan psikososial untuk pasien paliatif dan caregiver. Temukan ketenangan, makna, dan harapan setiap hari.</p>
        <div class="actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Masuk ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-outline">Daftar Akun Baru</a>
            @endauth
        </div>
    </div>
</x-guest-layout>
