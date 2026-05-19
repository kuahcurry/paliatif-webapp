<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ruang Hening') }}</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        
        <!-- PWA Meta Tags -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#4f9b4f">
        <link rel="apple-touch-icon" href="/images/logo.png">

        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js');
                });
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
                min-height: 100vh;
                background: #f5f7fb;
                color: #0f172a;
            }

            .auth-wrapper {
                display: grid;
                grid-template-columns: 1fr 1fr;
                min-height: 100vh;
            }

            /* ── Left branding panel ── */
            .auth-brand {
                background: linear-gradient(160deg, #e8f5e9 0%, #c8e6c9 30%, #a5d6a7 60%, #81c784 100%);
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 60px 48px;
                position: relative;
                overflow: hidden;
            }

            .auth-brand::before {
                content: '';
                position: absolute;
                top: -120px;
                right: -120px;
                width: 400px;
                height: 400px;
                border-radius: 50%;
                background: rgba(255,255,255,0.12);
            }

            .auth-brand::after {
                content: '';
                position: absolute;
                bottom: -80px;
                left: -80px;
                width: 300px;
                height: 300px;
                border-radius: 50%;
                background: rgba(255,255,255,0.08);
            }

            .brand-content {
                position: relative;
                z-index: 1;
                text-align: center;
                max-width: 380px;
            }

            .brand-logo {
                width: 120px;
                height: 120px;
                margin: 0 auto 28px;
                filter: drop-shadow(0 8px 24px rgba(47, 133, 90, 0.2));
            }

            .brand-title {
                font-size: 2rem;
                font-weight: 800;
                color: #1b5e20;
                margin-bottom: 8px;
                letter-spacing: -0.02em;
            }

            .brand-subtitle {
                font-size: 0.95rem;
                color: #2e7d32;
                font-weight: 500;
                margin-bottom: 32px;
            }

            .brand-tagline {
                font-size: 0.85rem;
                color: #33691e;
                line-height: 1.7;
                opacity: 0.85;
            }

            .brand-tagline strong {
                display: block;
                font-size: 1rem;
                margin-bottom: 6px;
                opacity: 1;
            }

            .brand-features {
                display: grid;
                gap: 14px;
                margin-top: 36px;
                text-align: left;
            }

            .brand-feature {
                display: flex;
                align-items: center;
                gap: 12px;
                background: rgba(255,255,255,0.45);
                backdrop-filter: blur(8px);
                padding: 12px 16px;
                border-radius: 14px;
                font-size: 0.82rem;
                color: #1b5e20;
                font-weight: 500;
            }

            .brand-feature-icon {
                width: 32px;
                height: 32px;
                border-radius: 10px;
                background: rgba(255,255,255,0.6);
                display: grid;
                place-items: center;
                flex-shrink: 0;
            }

            .brand-feature-icon svg {
                width: 16px;
                height: 16px;
                color: #2e7d32;
            }

            /* ── Right form panel ── */
            .auth-form-panel {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 48px 32px;
                background: #f5f7fb;
            }

            .auth-form-container {
                width: 100%;
                max-width: 420px;
            }

            .auth-form-header {
                margin-bottom: 32px;
            }

            .auth-form-header h2 {
                font-size: 1.6rem;
                font-weight: 800;
                color: #0f172a;
                margin-bottom: 6px;
                letter-spacing: -0.01em;
            }

            .auth-form-header p {
                font-size: 0.85rem;
                color: #64748b;
            }

            .auth-card {
                background: #ffffff;
                border-radius: 20px;
                padding: 28px 28px 24px;
                box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            }

            /* ── Form styling overrides ── */
            .auth-card label {
                display: block;
                font-size: 0.82rem;
                font-weight: 600;
                color: #334155;
                margin-bottom: 6px;
            }

            .auth-card input[type="email"],
            .auth-card input[type="text"],
            .auth-card input[type="password"],
            .auth-card select {
                width: 100%;
                padding: 11px 14px;
                border: 1.5px solid #e2e8f0;
                border-radius: 12px;
                font-size: 0.88rem;
                font-family: inherit;
                color: #0f172a;
                background: #f8fafc;
                transition: all 0.2s ease;
                outline: none;
            }

            .auth-card input:focus,
            .auth-card select:focus {
                border-color: #4f9b4f;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(79, 155, 79, 0.12);
            }

            .auth-card .mt-4 { margin-top: 16px; }
            .auth-card .mt-6 { margin-top: 24px; }
            .auth-card .block { display: block; }

            /* Checkbox */
            .auth-card input[type="checkbox"] {
                width: 16px;
                height: 16px;
                border-radius: 5px;
                border: 1.5px solid #cbd5e1;
                accent-color: #4f9b4f;
            }

            .auth-card .inline-flex { display: inline-flex; }
            .auth-card .items-center { align-items: center; }
            .auth-card .ms-2 { margin-left: 8px; }
            .auth-card .text-sm { font-size: 0.82rem; }

            /* Links */
            .auth-card a {
                color: #4f9b4f;
                font-weight: 600;
                text-decoration: none;
                transition: color 0.2s;
            }

            .auth-card a:hover {
                color: #2e7d32;
            }

            /* Error text */
            .auth-card .text-red-600 { color: #dc2626; }

            /* Primary button */
            .auth-card .auth-btn,
            .auth-card button[type="submit"],
            .auth-card .inline-flex.items-center.px-4 {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 11px 28px;
                background: linear-gradient(135deg, #4f9b4f 0%, #2e7d32 100%);
                color: #ffffff;
                font-weight: 700;
                font-size: 0.88rem;
                border: none;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.25s ease;
                text-transform: none;
                letter-spacing: 0;
                font-family: inherit;
                box-shadow: 0 4px 14px rgba(47, 133, 90, 0.25);
            }

            .auth-card button[type="submit"]:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(47, 133, 90, 0.35);
            }

            /* Bottom links */
            .auth-bottom {
                text-align: center;
                margin-top: 24px;
                font-size: 0.82rem;
                color: #64748b;
            }

            .auth-bottom a {
                color: #4f9b4f;
                font-weight: 700;
                text-decoration: none;
            }

            .auth-bottom a:hover {
                color: #2e7d32;
                text-decoration: underline;
            }

            /* ── Mobile logo for small screens ── */
            .auth-mobile-logo {
                display: none;
                text-align: center;
                margin-bottom: 24px;
            }

            .auth-mobile-logo img {
                width: 72px;
                height: 72px;
                margin: 0 auto;
                display: block;
            }

            .auth-mobile-logo h3 {
                font-size: 1.2rem;
                font-weight: 800;
                color: #2e7d32;
                margin-top: 10px;
            }

            /* ── Responsive ── */
            @media (max-width: 768px) {
                .auth-wrapper {
                    grid-template-columns: 1fr;
                }

                .auth-brand {
                    display: none;
                }

                .auth-mobile-logo {
                    display: block;
                }

                .auth-form-panel {
                    padding: 32px 20px;
                    background: linear-gradient(160deg, #f0fdf4 0%, #f5f7fb 50%, #fefce8 100%);
                }

                .auth-form-header {
                    text-align: center;
                }

                .auth-card {
                    padding: 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-wrapper">
            <!-- Left branding panel -->
            <div class="auth-brand">
                <div class="brand-content">
                    <img src="{{ asset('images/logo.png') }}" alt="Ruang Hening" class="brand-logo">
                    <h1 class="brand-title">Ruang Hening</h1>
                    <p class="brand-subtitle">Terapi Rohani Pasien Paliatif</p>

                    <div class="brand-tagline">
                        <strong>"Merawat ruh dengan kebaikan setiap hari"</strong>
                        Pendamping spiritual digital untuk perjalanan perawatan paliatif yang penuh makna dan kedamaian.
                    </div>

                    <div class="brand-features">
                        <div class="brand-feature">
                            <div class="brand-feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </div>
                            Dashboard Spiritual Harian
                        </div>
                        <div class="brand-feature">
                            <div class="brand-feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8-4-8-10c0-4 3.5-6 8-6s8 2 8 6c0 6-8 10-8 10z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                            </div>
                            Pohon Doa & Dukungan
                        </div>
                        <div class="brand-feature">
                            <div class="brand-feature-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                            </div>
                            Edukasi & Jurnal Refleksi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right form panel -->
            <div class="auth-form-panel">
                <div class="auth-form-container">
                    <div class="auth-mobile-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="Ruang Hening">
                        <h3>Ruang Hening</h3>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>

        <style>
            .password-wrapper {
                position: relative;
                display: flex;
                align-items: center;
            }

            .password-wrapper input {
                padding-right: 44px !important;
            }

            .password-toggle {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: #94a3b8;
                padding: 4px;
                display: grid;
                place-items: center;
                transition: color 0.2s ease;
            }

            .password-toggle:hover {
                color: #4f9b4f;
            }

            .password-toggle svg {
                width: 20px;
                height: 20px;
            }
        </style>

        <script>
            function togglePassword(fieldId, btn) {
                const input = document.getElementById(fieldId);
                const eyeOpen = btn.querySelector('.eye-open');
                const eyeClosed = btn.querySelector('.eye-closed');

                if (input.type === 'password') {
                    input.type = 'text';
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'block';
                } else {
                    input.type = 'password';
                    eyeOpen.style.display = 'block';
                    eyeClosed.style.display = 'none';
                }
            }
        </script>
    </body>
</html>
