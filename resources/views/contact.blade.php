@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
@endpush

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen contact-page">
    <div class="contact-layout">
        <aside class="contact-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="contact-main">
            <x-app-topbar
                class="contact-topbar"
                title="Hubungi Kami"
                subtitle="Informasi Kontak Pengembang & Pendamping"
                :badgeCount="0"
            />

            <div class="contact-content">
                <!-- Developer Card -->
                <div class="card contact-card">
                    <div class="contact-header">
                        <div class="contact-icon-wrap bg-primary-light">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <h4>Pengembang Aplikasi</h4>
                            <p class="role-badge">Developer Sistem</p>
                        </div>
                    </div>

                    <div class="contact-info-list">
                        <div class="info-item">
                            <div class="info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <small>Nama Lengkap</small>
                                <p>Aqief Hakimi</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <small>E-mail Kontak</small>
                                <p><a href="mailto:tbot691@gmail.com" class="link-styled">tbot691@gmail.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Card -->
                <div class="card contact-card">
                    <div class="contact-header">
                        <div class="contact-icon-wrap bg-success-light">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div>
                            <h4>Dukungan Medis & Spiritual</h4>
                            <p class="role-badge success">Tim Pendamping</p>
                        </div>
                    </div>

                    <p class="support-text">
                        Untuk konsultasi mengenai terapi rohani pasien paliatif, bimbingan emosional, atau bantuan perawatan lainnya, Anda dapat menghubungi tim pendamping yang terdaftar di sistem.
                    </p>

                    <div class="contact-info-list">
                        <div class="info-item">
                            <div class="info-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8-4-8-10c0-4 3.5-6 8-6s8 2 8 6c0 6-8 10-8 10z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                            </div>
                            <div>
                                <small>Layanan Utama</small>
                                <p>Konseling & Pendampingan Paliatif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        .contact-page {
            background: #f4f6fb;
            font-family: 'Outfit', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .contact-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .contact-sidebar {
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

        .nav-item.is-active,
        .nav-item:hover {
            background: #e1f1e1;
            color: #256c32;
        }

        .nav-group {
            display: grid;
            gap: 6px;
        }

        .nav-sub {
            display: grid;
            gap: 6px;
            margin-left: 12px;
        }

        .nav-sub-item {
            font-size: 0.82rem;
            color: var(--muted);
            text-decoration: none;
        }

        .nav-sub-item.is-active {
            color: #256c32;
            font-weight: 600;
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

        .contact-main {
            padding: 26px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .contact-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .contact-topbar .topbar-title-wrapper {
            grid-column: 2;
            text-align: center;
        }

        .contact-topbar .ghost-button {
            grid-column: 1;
            justify-self: start;
        }

        .contact-topbar .topbar-actions {
            grid-column: 3;
            justify-self: end;
        }

        .contact-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .contact-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
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
            border: none;
            cursor: pointer;
            font-family: inherit;
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
            font-size: 0.85rem;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            text-align: left;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--muted);
            text-align: left;
        }

        .chevron {
            color: var(--muted);
        }

        .contact-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .card {
            background: var(--surface);
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .contact-header {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 16px;
        }

        .contact-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #475569;
        }

        .bg-primary-light {
            background: #e9f6e9;
            color: #4f9b4f;
        }

        .bg-success-light {
            background: #f0fdf4;
            color: #16a34a;
        }

        .contact-icon-wrap svg {
            width: 24px;
            height: 24px;
        }

        .contact-header h4 {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text);
            margin: 0;
        }

        .role-badge {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 999px;
            background: #e9f6e9;
            color: #2e622e;
            margin: 4px 0 0 0;
        }

        .role-badge.success {
            background: #dcfce7;
            color: #166534;
        }

        .contact-info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #f8fafc;
            display: grid;
            place-items: center;
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .info-icon svg {
            width: 18px;
            height: 18px;
        }

        .info-item small {
            font-size: 0.72rem;
            color: var(--muted);
            display: block;
        }

        .info-item p {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }

        .link-styled {
            color: var(--accent);
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .link-styled:hover {
            color: #2e622e;
            text-decoration: underline;
        }

        .support-text {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
            margin: 0;
        }

        @media (max-width: 900px) {
            .contact-layout {
                grid-template-columns: 1fr;
            }

            .contact-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                flex-direction: row;
                overflow-x: auto;
                gap: 12px;
            }

            .contact-main {
                padding: 16px;
            }

            .contact-topbar {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .contact-topbar .topbar-title-wrapper {
                text-align: left;
            }
        }
    </style>
</x-app-layout>
