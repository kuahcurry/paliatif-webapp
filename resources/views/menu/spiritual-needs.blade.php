@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $patientName = Auth::user()->name ?? 'Bapak Supriyono';
    $patientAge = '62 Tahun';
    $patientGender = 'Laki-laki';
    $patientRm = 'No. RM: 23051567';
    $patientRoom = 'Ruang: Mawar 3';
    $nurseName = Auth::user()->name ?? 'Siti Rahmawati';
    $nurseRole = 'Perawat';
    $interventionSteps = [
        ['title' => 'Penguatan Harapan', 'desc' => 'Latihan refleksi harapan dan rasa syukur.', 'status' => 'done'],
        ['title' => 'Makna Hidup', 'desc' => 'Membantu pasien menemukan makna harian.', 'status' => 'current'],
        ['title' => 'Doa & Dzikir', 'desc' => 'Penguatan spiritual sesuai keyakinan.', 'status' => 'upcoming'],
        ['title' => 'Dukungan Keluarga', 'desc' => 'Kolaborasi dengan caregiver.', 'status' => 'upcoming'],
    ];
    $todayPrograms = [
        ['label' => 'Doa Pagi', 'detail' => '5-7 menit doa dan afirmasi.'],
        ['label' => 'Dzikir Singkat', 'detail' => '3 menit untuk menenangkan pikiran.'],
        ['label' => 'Refleksi Syukur', 'detail' => 'Menulis 3 hal yang disyukuri.'],
    ];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen intervention-page">
    <div class="intervention-layout">
        <aside class="intervention-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="intervention-main">
            <x-app-topbar
                class="intervention-topbar"
                title="Kebutuhan Spiritual"
                subtitle="Intervensi spiritual harian pasien paliatif"
                :showMenuButton="true"
                :badgeCount="3"
            />

            <section class="card patient-card">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
                        <p class="muted">{{ $patientRm }} - {{ $patientRoom }}</p>
                    </div>
                </div>
                <div class="patient-metrics">
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12zm0 2.5c-4 0-7.5 2-7.5 4.5V22h15v-3c0-2.5-3.5-4.5-7.5-4.5z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Perawat Penanggung Jawab</p>
                            <p class="metric-value">{{ $nurseName }}</p>
                            <p class="metric-sub">{{ $nurseRole }}</p>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12zm0 18a5 5 0 0 1-5-5c0-2.9 1.7-5.7 5-9 3.3 3.3 5 6.1 5 9a5 5 0 0 1-5 5z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Fokus Intervensi</p>
                            <p class="metric-value">Harapan & Makna Hidup</p>
                            <p class="metric-sub">Diperbarui: {{ now()->format('H:i') }} WIB</p>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 7h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7zm2 4h4v4H6z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Program Hari Ini</p>
                            <p class="metric-value">3 Aktivitas</p>
                            <p class="metric-sub">Tersusun dari rekomendasi harian</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="intervention-body">
                <div class="intervention-left">
                    <div class="card">
                        <div class="section-header">
                            <div>
                                <h4>Pohon Spiritual</h4>
                                <p>Pohon spiritual akan terus bertumbuh seiring perjalanan pengguna.</p>
                            </div>
                            <span class="status-pill">Tahap 2 dari 4</span>
                        </div>

                        <div class="highlight-card">
                            <h5>Harapan dan motivasi harian</h5>
                            <p>Konten berupa video dengan narasi teks akan muncul sesuai hasil pengkajian.</p>
                            <p>Rekomendasi akan disesuaikan dengan kebutuhan pengguna agar lebih relevan.</p>
                        </div>

                        <div class="tree-list">
                            @foreach ($interventionSteps as $step)
                                <div class="tree-item {{ $step['status'] }}">
                                    <div class="tree-dot"></div>
                                    <div>
                                        <h6>{{ $step['title'] }}</h6>
                                        <p>{{ $step['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card">
                        <div class="section-header">
                            <div>
                                <h4>Program Intervensi Hari Ini</h4>
                                <p>Rangkaian aktivitas singkat yang dapat dilakukan pasien.</p>
                            </div>
                        </div>
                        <div class="program-grid">
                            @foreach ($todayPrograms as $program)
                                <div class="program-card">
                                    <h5>{{ $program['label'] }}</h5>
                                    <p>{{ $program['detail'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <aside class="intervention-right">
                    <div class="card">
                        <div class="section-header">
                            <div>
                                <h4>Panduan Caregiver</h4>
                                <p>Akses modul edukasi spiritual-psikososial yang direkomendasikan.</p>
                            </div>
                        </div>
                        <div class="caregiver-cta">
                            <div>
                                <p>Butuh panduan bagi caregiver?</p>
                                <small>Modul edukasi disusun agar pendamping lebih percaya diri.</small>
                            </div>
                            <a href="{{ route('education.index') }}" class="primary-button">Buka modul edukasi</a>
                        </div>
                    </div>

                    <div class="card note-card">
                        <h4>Catatan Layanan</h4>
                        <p>Konten layanan spiritual akan disusun bertahap bersama tim pendamping.</p>
                        <p>Pastikan pasien merasa nyaman dan aman selama intervensi.</p>
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

        .intervention-page {
            background: #f4f6fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .intervention-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .intervention-sidebar {
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
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #dff3df;
            display: grid;
            place-items: center;
        }

        .brand-icon span {
            width: 14px;
            height: 14px;
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

        .intervention-main {
            padding: 26px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .intervention-left,
        .intervention-right {
            display: grid;
            gap: 8px;
        }

        .intervention-topbar {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            gap: 16px;
        }

        .intervention-topbar > div:first-of-type {
            text-align: center;
        }

        .intervention-topbar .ghost-button {
            position: absolute;
            left: 0;
        }

        .intervention-topbar .topbar-actions {
            position: absolute;
            right: 0;
        }

        .intervention-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .intervention-topbar p {
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

        .chevron {
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

        .patient-card {
            display: grid;
            grid-template-columns: 1.2fr 2fr;
            gap: 20px;
            align-items: center;
        }

        .patient-profile {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .patient-avatar {
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

        .patient-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .metric {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
            background: #f9fafb;
            border-radius: 16px;
            padding: 12px;
        }

        .metric-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: #e5f6e5;
            display: grid;
            place-items: center;
            color: #3f7a3f;
        }

        .metric-icon svg {
            width: 18px;
            height: 18px;
        }

        .metric-label {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .metric-value {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text);
        }

        .metric-sub {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .intervention-body {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .section-header h4 {
            font-weight: 700;
        }

        .section-header p {
            font-size: 0.78rem;
            color: var(--muted);
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

        .highlight-card {
            background: #f0fdf4;
            border-radius: 16px;
            padding: 16px;
            display: grid;
            gap: 8px;
            color: #166534;
        }

        .highlight-card h5 {
            font-weight: 700;
        }

        .highlight-card p {
            font-size: 0.78rem;
        }

        .tree-list {
            display: grid;
            gap: 12px;
        }

        .tree-item {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px;
            align-items: start;
        }

        .tree-dot {
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: #e2e8f0;
            margin-top: 4px;
        }

        .tree-item.done .tree-dot {
            background: #22c55e;
        }

        .tree-item.current .tree-dot {
            background: #4f9b4f;
        }

        .tree-item h6 {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .tree-item p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .program-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .program-card {
            border-radius: 16px;
            background: #f8fafc;
            padding: 12px;
            display: grid;
            gap: 6px;
        }

        .program-card h5 {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .program-card p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .caregiver-cta {
            display: grid;
            gap: 12px;
        }

        .caregiver-cta p {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
        }

        .caregiver-cta small {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .primary-button {
            background: #4f9b4f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .note-card {
            background: #f8fafc;
            color: #475569;
        }

        .note-card p {
            font-size: 0.78rem;
            color: var(--muted);
        }

        @media (max-width: 1200px) {
            .patient-card {
                grid-template-columns: 1fr;
            }

            .patient-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .intervention-body {
                grid-template-columns: 1fr;
            }

            .program-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .intervention-layout {
                grid-template-columns: 1fr;
            }

            .intervention-sidebar {
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

            .intervention-topbar {
                flex-direction: column;
                align-items: flex-start;
                justify-content: space-between;
            }

            .intervention-topbar > div:first-of-type {
                text-align: left;
            }

            .intervention-topbar .ghost-button,
            .intervention-topbar .topbar-actions {
                position: static;
            }
        }
    </style>
</x-app-layout>
