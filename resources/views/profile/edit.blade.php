@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
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

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen dashboard-page">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="dashboard-main profile-page">
            <x-app-topbar
                class="dashboard-topbar"
                title="Pengaturan Akun"
                subtitle="Kelola profil dan keamanan Anda"
                :badgeCount="0"
            />

            <section class="profile-hero">
                <div class="profile-avatar">{{ strtoupper(substr($userName, 0, 1)) }}</div>
                <div class="profile-info">
                    <h3>{{ $userName }}</h3>
                    <p class="profile-email">{{ $userEmail }}</p>
                    <span class="status-badge {{ $statusTone }}">{{ $statusLabel }}</span>
                </div>
                <div class="profile-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    <span>Perbarui informasi akun secara berkala untuk keamanan.</span>
                </div>
            </section>

            <section class="settings-body">
                <div class="settings-main-column">
                    <div class="card form-card">
                        <div class="card-header">
                            <h4>Informasi Profil</h4>
                            <p>Perbarui informasi profil dan alamat email akun Anda.</p>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="card form-card">
                        <div class="card-header">
                            <h4>Perbarui Kata Sandi</h4>
                            <p>Pastikan akun Anda menggunakan kata sandi yang kuat dan acak.</p>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <aside class="settings-side">
                    <div class="card tips-card">
                        <div class="tips-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10c0-4-3.5-6-8-6s-8 2-8 6c0 6 8 10 8 10z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                            <h4>Tips Keamanan</h4>
                        </div>
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
            --accent-soft: #e9f6e9;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .dashboard-page {
            background: #f5f7fb;
            font-family: 'Outfit', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .dashboard-sidebar {
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

        .sidebar-brand h1 {
            font-size: 0.95rem;
            color: var(--text);
        }

        .sidebar-brand p {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
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
            transition: all 0.2s ease;
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

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
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
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
        }

        .icon-button svg {
            width: 20px;
            height: 20px;
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
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
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #dbeafe;
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

        .chevron {
            color: var(--muted);
        }

        .dashboard-topbar {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .dashboard-topbar .topbar-title-wrapper {
            text-align: center;
        }

        .dashboard-topbar .topbar-actions {
            position: absolute;
            right: 0;
        }

        .dashboard-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .dashboard-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .dashboard-main {
            padding: 28px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .profile-page .dashboard-main {
            gap: 20px;
        }

        .profile-hero {
            display: flex;
            align-items: center;
            gap: 18px;
            background: var(--surface);
            border-radius: 20px;
            padding: 24px 28px;
            box-shadow: var(--shadow);
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #dff3df, #c8e6c8);
            display: grid;
            place-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2f855a;
            flex-shrink: 0;
        }

        .profile-info {
            flex: 1;
        }

        .profile-info h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text);
        }

        .profile-email {
            font-size: 0.8rem;
            color: var(--muted);
            margin: 2px 0 6px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-badge.good {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.warn {
            background: #fee2e2;
            color: #b91c1c;
        }

        .profile-hint {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 0.78rem;
            flex-shrink: 0;
            max-width: 220px;
        }

        .profile-hint svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            color: #94a3b8;
        }

        .settings-body {
            display: grid;
            grid-template-columns: 2.1fr 1fr;
            gap: 20px;
        }

        .settings-main-column {
            display: grid;
            gap: 20px;
        }

        .card {
            background: var(--surface);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
        }

        .card-header {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eef2f6;
        }

        .card-header h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 2px;
        }

        .card-header p {
            font-size: 0.8rem;
            color: var(--muted);
            margin: 0;
        }

        .card-body > section header {
            display: none;
        }

        .card-body form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card-body form > div {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .card-body form label,
        .card-body form .text-sm.font-medium.text-gray-900 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-body form input:not([type="radio"]):not([type="checkbox"]),
        .card-body form select,
        .card-body form textarea {
            border: 1px solid #d1dbe6;
            border-radius: 12px;
            padding: 10px 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            width: 100%;
            color: #1e293b;
            background: #fff;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .card-body form input:focus,
        .card-body form select:focus,
        .card-body form textarea:focus {
            outline: none;
            border-color: #4f9b4f;
            box-shadow: 0 0 0 3px rgba(79, 155, 79, 0.15);
        }

        .card-body form .border-t {
            border-top: 1px solid #eef2f6;
            padding-top: 16px;
            margin-top: 4px;
        }

        .card-body form .border-t h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: #2d6a4f;
            margin-bottom: 12px;
        }

        .card-body form .grid.grid-cols-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .card-body form button[type="submit"] {
            padding: 10px 28px;
            background: #4f9b4f;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            transition: background 0.15s;
            align-self: flex-start;
        }

        .card-body form button[type="submit"]:hover {
            background: #3d8b3d;
        }

        .card-body form .inline-flex {
            margin-top: 8px;
        }

        .card-body form .text-sm.text-gray-600 {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .card-body form .text-green-600 {
            color: #2f855a;
        }

        .card-body form .mt-2 {
            margin-top: 4px;
        }

        .data-diri {
            border-top: 1px solid #eef2f6;
            padding-top: 16px;
            margin-top: 4px;
        }

        .data-diri h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: #2d6a4f;
            margin: 0 0 12px;
        }

        .data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .verify-note {
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 8px;
        }

        .verify-link {
            text-decoration: underline;
            background: none;
            border: none;
            color: #4f9b4f;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem;
            padding: 0;
        }

        .verify-sent {
            font-size: 0.8rem;
            color: #2f855a;
            margin-top: 8px;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 8px;
        }

        .saved-notice {
            font-size: 0.8rem;
            color: #2f855a;
        }

        .card-body form .text-red-600,
        .card-body form [x-show="errors"] {
            font-size: 0.75rem;
            color: #dc2626;
        }

        .settings-side {
            display: grid;
            gap: 20px;
            align-content: start;
        }

        .tips-card {
            background: linear-gradient(135deg, #f0f9f0 0%, #eaf7ea 100%);
            border: 1px solid #d4edda;
        }

        .tips-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .tips-header svg {
            width: 22px;
            height: 22px;
            color: #4f9b4f;
            flex-shrink: 0;
        }

        .tips-header h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a4731;
            margin: 0;
        }

        .tips-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
            font-size: 0.78rem;
            color: #4a7a5a;
        }

        .tips-card ul li {
            padding-left: 18px;
            position: relative;
        }

        .tips-card ul li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 7px;
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #4f9b4f;
        }

        .danger-card {
            border: 1px solid #fecaca;
            background: #fef2f2;
        }

        .danger-card section header h2 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #991b1b;
            margin: 0 0 4px;
        }

        .danger-card section header p {
            font-size: 0.78rem;
            color: #b91c1c;
            margin: 0;
        }

        .danger-card button {
            padding: 10px 20px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            transition: background 0.15s;
            margin-top: 12px;
        }

        .danger-card button:hover {
            background: #b91c1c;
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
            font-family: 'Outfit', sans-serif;
        }

        .danger-btn {
            padding: 10px 20px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            transition: background 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .danger-btn:hover {
            background: #b91c1c;
        }

        .confirm-overlay {
            position: fixed;
            inset: 0;
            overflow-y: auto;
            padding: 24px;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .confirm-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            transition: opacity 0.3s;
        }

        .confirm-dialog {
            position: relative;
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
        }

        .confirm-dialog h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 6px;
        }

        .confirm-dialog > p {
            font-size: 0.8rem;
            color: var(--muted);
            margin: 0 0 18px;
        }

        .confirm-password {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 18px;
        }

        .confirm-password label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
        }

        .confirm-password input {
            border: 1px solid #d1dbe6;
            border-radius: 12px;
            padding: 10px 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            width: 75%;
        }

        .confirm-password input:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
        }

        .confirm-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        @media (max-width: 1200px) {
            .profile-hero {
                flex-wrap: wrap;
            }

            .profile-hint {
                max-width: none;
                width: 100%;
            }

            .settings-body {
                grid-template-columns: 1fr;
            }

            .dashboard-topbar {
                flex-direction: column;
                align-items: flex-start;
                justify-content: space-between;
            }

            .dashboard-topbar .topbar-title-wrapper {
                text-align: left;
            }

            .dashboard-topbar .topbar-actions {
                position: static;
            }
        }

        @media (max-width: 900px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .dashboard-main {
                padding: 16px 12px 32px;
            }

            .card {
                padding: 16px 12px;
            }
        }
    </style>
</x-app-layout>
