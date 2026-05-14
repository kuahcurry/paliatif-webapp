@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $userModel = Auth::user();
    $userName = $userModel?->name ?? 'Pengguna';
    $userEmail = $userModel?->email ?? 'email@contoh.com';
    $emailVerified = $userModel && method_exists($userModel, 'hasVerifiedEmail')
        ? $userModel->hasVerifiedEmail()
        : true;
    $statusLabel = $emailVerified ? 'Email terverifikasi' : 'Email belum terverifikasi';
    $statusTone = $emailVerified ? 'good' : 'warn';
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen settings-page">
    <div class="settings-layout">
        <aside class="settings-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="settings-main">
            <x-app-topbar
                class="settings-topbar"
                title="Pengaturan Akun"
                subtitle="Kelola profil dan keamanan Anda"
                :showMenuButton="true"
                :badgeCount="0"
            />

            <section class="card profile-card">
                <div class="profile-main">
                    <div class="profile-avatar">{{ strtoupper(substr($userName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $userName }}</h3>
                        <p class="muted">{{ $userEmail }}</p>
                    </div>
                </div>
                <div class="profile-status">
                    <span class="status-pill {{ $statusTone }}">{{ $statusLabel }}</span>
                    <p class="muted">Perbarui informasi akun secara berkala untuk keamanan.</p>
                </div>
            </section>

            <section class="settings-body">
                <div class="settings-main-column">
                    <div class="card form-card">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div class="card form-card">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <aside class="settings-side">
                    <div class="card tips-card">
                        <h4>Tips Keamanan</h4>
                        <ul>
                            <li>Gunakan kata sandi minimal 8 karakter dengan kombinasi huruf dan angka.</li>
                            <li>Perbarui kata sandi secara berkala untuk menjaga keamanan akun.</li>
                            <li>Jangan membagikan informasi login kepada pihak lain.</li>
                        </ul>
                    </div>

                    <div class="card danger-card">
                        @include('profile.partials.delete-user-form')
                    </div>
                </aside>
            </section>
        </main>
    </div>

    <style>
        :root {
            --surface: #ffffff;
            --surface-muted: #f7faf5;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --accent: #4f9b4f;
            --shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .settings-page {
            background: #f4f6fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .settings-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .settings-sidebar {
            background: var(--surface);
            padding: 28px 20px;
            border-right: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sidebar-brand {
            display: flex;
            gap: 12px;
            align-items: center;
            font-weight: 700;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            background: #dff3df;
            display: grid;
            place-items: center;
        }

        .brand-icon span {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            background: #63b96b;
            display: block;
        }

        .sidebar-brand h1 {
            font-size: 0.95rem;
            color: var(--text);
        }

        .sidebar-brand p {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .sidebar-brand small {
            font-size: 0.7rem;
            color: #94a3b8;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .nav-item.is-active,
        .nav-item:hover {
            background: #e1f1e1;
            color: #256c32;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: grid;
            place-items: center;
            color: currentColor;
        }

        .nav-icon svg {
            width: 18px;
            height: 18px;
        }

        .sidebar-footer {
            margin-top: auto;
        }

        .footer-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .footer-title {
            font-weight: 700;
            color: var(--text);
        }

        .footer-subtitle {
            margin-bottom: 6px;
        }

        .settings-main {
            padding: 26px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .settings-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .settings-topbar > div:not(.topbar-actions) {
            grid-column: 2;
            text-align: center;
        }

        .settings-topbar .ghost-button {
            grid-column: 1;
            justify-self: start;
        }

        .settings-topbar .topbar-actions {
            grid-column: 3;
            justify-self: end;
        }

        .settings-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .settings-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .ghost-button {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-button {
            position: relative;
            border: none;
            background: #ffffff;
            box-shadow: var(--shadow);
            border-radius: 12px;
            width: 38px;
            height: 38px;
            cursor: pointer;
            display: grid;
            place-items: center;
            color: #475569;
        }

        .icon-button svg {
            width: 20px;
            height: 20px;
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #22c55e;
            color: #ffffff;
            font-size: 0.65rem;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            display: grid;
            place-items: center;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border-radius: 16px;
            padding: 6px 12px;
            box-shadow: var(--shadow);
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #1d4ed8;
            font-weight: 700;
            display: grid;
            place-items: center;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .profile-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .profile-main {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #e2f4e2;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
            font-weight: 700;
            color: #2f855a;
        }

        .muted {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .profile-status {
            display: grid;
            gap: 6px;
            text-align: right;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
        }

        .status-pill.warn {
            background: #fee2e2;
            color: #b91c1c;
        }

        .settings-body {
            display: grid;
            grid-template-columns: 2.1fr 1fr;
            gap: 20px;
        }

        .settings-main-column,
        .settings-side {
            display: grid;
            gap: 8px;
        }

        .form-card section header h2 {
            font-weight: 700;
        }

        .form-card section header p {
            font-size: 0.8rem;
        }

        .settings-side {
            display: grid;
            gap: 20px;
        }

        .tips-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 8px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .danger-card {
            border: 1px solid #fee2e2;
        }

        @media (max-width: 1200px) {
            .profile-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-status {
                text-align: left;
            }

            .settings-body {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .settings-layout {
                grid-template-columns: 1fr;
            }

            .settings-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                flex-direction: row;
                overflow-x: auto;
                gap: 12px;
            }

            .sidebar-brand,
            .sidebar-footer {
                display: none;
            }

            .sidebar-nav {
                flex-direction: row;
                flex-wrap: nowrap;
            }

            .settings-topbar {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .settings-topbar > div:not(.topbar-actions) {
                text-align: left;
            }

            .settings-topbar .topbar-actions {
                justify-self: auto;
                align-self: flex-start;
            }
        }
    </style>
</x-app-layout>
