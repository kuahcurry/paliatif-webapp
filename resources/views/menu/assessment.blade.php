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
    $assessmentDate = now()->format('d M Y');
    $assessmentTime = now()->format('H:i');
    $assessmentBy = Auth::user()->name ?? 'Siti Rahmawati';
    $assessmentRole = 'Perawat';
    $statusLabel = $latestAssessment ? 'Dalam Proses' : 'Belum Dimulai';
    $statusTone = $latestAssessment ? 'status-pill success' : 'status-pill warning';
    $lastSaved = $latestAssessment?->updated_at?->format('H:i') ?? now()->format('H:i');
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen dashboard-page">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <span></span>
                </div>
                <div>
                    <h1>Terapi Rohani</h1>
                    <p>Pasien Paliatif</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-item" href="{{ route('dashboard') }}">
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" fill="currentColor"/></svg>
                    </span>
                    {{ __('Beranda') }}
                </a>

                <div class="nav-group">
                    <div class="nav-item is-active">
                        <span class="nav-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm8 1v4h4" fill="currentColor"/></svg>
                        </span>
                        {{ __('Pengkajian') }}
                    </div>
                    <div class="nav-sub">
                        <a class="nav-sub-item is-active" href="{{ route('menu.assessment') }}">{{ __('Pengkajian Awal') }}</a>
                        <a class="nav-sub-item" href="#">SWBS</a>
                        <a class="nav-sub-item" href="#">ECOG</a>
                        <a class="nav-sub-item" href="#">ESAS</a>
                    </div>
                </div>

                <a class="nav-item" href="{{ route('menu.spiritual-needs') }}">
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l2.2 5.4 5.8.5-4.4 3.8 1.4 5.7L12 15.8 7 18.4l1.4-5.7L4 8.9l5.8-.5z" fill="currentColor"/></svg>
                    </span>
                    {{ __('Intervensi') }}
                </a>
                <a class="nav-item" href="{{ route('menu.emotional-evaluation') }}">
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16a1 1 0 0 1 1 1v12l-4-3H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" fill="currentColor"/></svg>
                    </span>
                    {{ __('Evaluasi') }}
                </a>
                <a class="nav-item" href="#">
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 7h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7zm2 4h4v4H6z" fill="currentColor"/></svg>
                    </span>
                    {{ __('Jadwal & Aktivitas') }}
                </a>
                <a class="nav-item" href="{{ route('education.index') }}">
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6l9-4 9 4-9 4-9-4zm0 5l9 4 9-4v7l-9 4-9-4v-7z" fill="currentColor"/></svg>
                    </span>
                    {{ __('Referensi') }}
                </a>
                <a class="nav-item" href="#">
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16v2H4zM5 4h4v12H5zM10 10h4v6h-4zM15 7h4v9h-4z" fill="currentColor"/></svg>
                    </span>
                    {{ __('Laporan') }}
                </a>
                <a class="nav-item" href="{{ route('profile.edit') }}">
                    <span class="nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm8 8.5V22H4v-5.5A6.5 6.5 0 0 1 10.5 10h3A6.5 6.5 0 0 1 20 16.5z" fill="currentColor"/></svg>
                    </span>
                    {{ __('Pengaturan') }}
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="footer-card">
                    <p class="footer-title">RS PKU Muhammadiyah</p>
                    <p class="footer-subtitle">Gombong</p>
                    <p class="footer-text">Melayani dengan iman, profesional dan humanis.</p>
                </div>
            </div>
        </aside>

        <main class="dashboard-main">
            <header class="dashboard-topbar">
                <div>
                    <h2>Pengkajian Awal</h2>
                    <p>Terapi Rohani Pasien Paliatif</p>
                </div>
                <div class="topbar-actions">
                    <button class="icon-button" type="button" aria-label="Notifikasi">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2zm7-6V11a7 7 0 1 0-14 0v5l-2 2v1h18v-1z" fill="currentColor"/></svg>
                        <span class="badge">3</span>
                    </button>
                    <button class="icon-button" type="button" aria-label="Bantuan">?</button>
                    <div class="user-chip">
                        <div class="avatar">{{ strtoupper(substr($assessmentBy, 0, 1)) }}</div>
                        <div>
                            <div class="user-name">{{ $assessmentBy }}</div>
                            <div class="user-role">{{ $assessmentRole }}</div>
                        </div>
                        <span class="chevron">v</span>
                    </div>
                </div>
            </header>

            @if (session('status') === 'assessment-saved')
                <div class="alert success">{{ __('Pengkajian caregiver tersimpan.') }}</div>
            @endif

            <section class="card assessment-hero">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
                        <p class="muted">{{ $patientRm }} - {{ $patientRoom }}</p>
                    </div>
                </div>
                <div class="hero-metrics">
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 7h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Tanggal Pengkajian</p>
                            <p class="metric-value">{{ $assessmentDate }}</p>
                            <p class="metric-sub">{{ $assessmentTime }} WIB</p>
                            <a class="metric-link" href="#">Ubah</a>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12zm0 2.5c-4 0-7.5 2-7.5 4.5V22h15v-3c0-2.5-3.5-4.5-7.5-4.5z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Dilakukan oleh</p>
                            <p class="metric-value">{{ $assessmentBy }}</p>
                            <p class="metric-sub">{{ $assessmentRole }}</p>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 2h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Status Pengkajian</p>
                            <span class="{{ $statusTone }}">{{ $statusLabel }}</span>
                            <p class="metric-sub">Simpan terakhir: {{ $lastSaved }} WIB</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="assessment-content">
                <div class="assessment-main">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4>Pilih Instrumen Pengkajian</h4>
                                <p>Silakan pilih instrumen untuk melakukan pengkajian awal pasien.</p>
                            </div>
                        </div>

                        <div class="instrument-grid">
                            <div class="instrument-card green">
                                <div class="instrument-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-6-3.5-8-7.5C2 8.5 5 6 8 6c1.7 0 3.2 1 4 2.3C12.8 7 14.3 6 16 6c3 0 6 2.5 4 7.5-2 4-8 7.5-8 7.5z" fill="currentColor"/></svg>
                                </div>
                                <h5>Spiritual Well Being Scale (SWBS)</h5>
                                <p>Mengukur kesejahteraan spiritual pasien dalam hubungan dengan diri sendiri, orang lain, dan Tuhan.</p>
                                <button type="button">Mulai Pengkajian</button>
                            </div>

                            <div class="instrument-card blue">
                                <div class="instrument-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4h10v4H7zM5 10h14v10H5z" fill="currentColor"/></svg>
                                </div>
                                <h5>Eastern Cooperative Oncology Group (ECOG)</h5>
                                <p>Menilai tingkat kemampuan pasien dalam beraktivitas sehari-hari dan kondisi fungsional.</p>
                                <button type="button">Mulai Pengkajian</button>
                            </div>

                            <div class="instrument-card purple">
                                <div class="instrument-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 3h12v18H6z" fill="currentColor"/></svg>
                                </div>
                                <h5>Edmonton Symptom Assessment System (ESAS)</h5>
                                <p>Menilai intensitas gejala fisik dan emosional yang dialami pasien.</p>
                                <button type="button">Mulai Pengkajian</button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4>Riwayat Pengkajian</h4>
                                <p>Riwayat pengkajian awal yang telah dilakukan.</p>
                            </div>
                        </div>

                        <div class="table-wrap">
                            <table class="history-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Instrumen</th>
                                        <th>Dilakukan oleh</th>
                                        <th>Hasil</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>10 Mei 2024, 14:20</td>
                                        <td><span class="pill green">SWBS</span></td>
                                        <td>{{ $assessmentBy }}</td>
                                        <td><span class="pill success">Selesai</span></td>
                                        <td><button class="ghost-button" type="button">Lihat</button></td>
                                    </tr>
                                    <tr>
                                        <td>10 Mei 2024, 14:45</td>
                                        <td><span class="pill blue">ECOG</span></td>
                                        <td>{{ $assessmentBy }}</td>
                                        <td><span class="pill success">Selesai</span></td>
                                        <td><button class="ghost-button" type="button">Lihat</button></td>
                                    </tr>
                                    <tr>
                                        <td>10 Mei 2024, 15:10</td>
                                        <td><span class="pill purple">ESAS</span></td>
                                        <td>{{ $assessmentBy }}</td>
                                        <td><span class="pill success">Selesai</span></td>
                                        <td><button class="ghost-button" type="button">Lihat</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="muted small">Pengkajian awal sebaiknya dilakukan pada awal perawatan dan dievaluasi secara berkala.</p>
                    </div>

                    <details class="accordion">
                        <summary>Pengkajian Caregiver Singkat (opsional)</summary>
                        <form method="POST" action="{{ route('menu.assessment.store') }}" class="form-grid">
                            @csrf

                            @php
                                $assessmentQuestions = [
                                    'anxiety_level' => 'Tingkat kecemasan caregiver',
                                    'grief_level' => 'Tingkat duka yang dirasakan',
                                    'communication_level' => 'Kesulitan berkomunikasi dengan pasien/keluarga',
                                ];
                            @endphp

                            @foreach ($assessmentQuestions as $field => $label)
                                <div class="form-row">
                                    <label>{{ $label }}</label>
                                    <div class="radio-group">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label>
                                                <input type="radio" name="{{ $field }}" value="{{ $i }}" @checked(old($field) == $i) @if ($i === 1) required @endif>
                                                <span>{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get($field)" />
                                </div>
                            @endforeach

                            <button type="submit" class="primary-button">Simpan pengkajian</button>
                        </form>
                    </details>
                </div>

                <aside class="assessment-side">
                    <div class="card info-card">
                        <h4>Tentang Pengkajian Awal</h4>
                        <p>
                            Pengkajian awal dilakukan untuk memahami kondisi spiritual, fungsional, dan gejala pasien secara komprehensif, sebagai dasar perencanaan terapi rohani yang tepat dan personal.
                        </p>
                        <div class="info-list">
                            <h5>Petunjuk Pengisian</h5>
                            <ul>
                                <li>Pilih instrumen yang akan digunakan.</li>
                                <li>Jawab setiap pertanyaan dengan jujur sesuai kondisi pasien.</li>
                                <li>Pastikan semua item terisi sebelum menyimpan.</li>
                                <li>Hasil pengkajian menjadi dasar perencanaan intervensi.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card quote-card">
                        <p>"Dan mohonlah pertolongan (kepada Allah) dengan sabar dan shalat."</p>
                        <span>(QS. Al-Baqarah: 45)</span>
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
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .dashboard-sidebar {
            background: var(--surface-muted);
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
            width: 40px;
            height: 40px;
            border-radius: 14px;
            background: #dff3df;
            display: grid;
            place-items: center;
        }

        .brand-icon span {
            width: 20px;
            height: 20px;
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

        .nav-group {
            display: grid;
            gap: 8px;
        }

        .nav-sub {
            display: grid;
            gap: 6px;
            margin-left: 34px;
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
            background: #ffffff;
            border-radius: 16px;
            padding: 16px;
            font-size: 0.75rem;
            color: var(--muted);
            box-shadow: var(--shadow);
        }

        .footer-title {
            font-weight: 700;
            color: var(--text);
        }

        .footer-subtitle {
            margin-bottom: 6px;
        }

        .dashboard-main {
            padding: 28px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .dashboard-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .dashboard-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
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

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            background: #ecfdf3;
            color: #15803d;
            font-size: 0.85rem;
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

        .assessment-hero {
            display: grid;
            grid-template-columns: 1.1fr 2fr;
            gap: 20px;
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

        .hero-metrics {
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

        .metric-link {
            font-size: 0.7rem;
            color: #2f855a;
        }

        .status-pill {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-pill.success { background: #dcfce7; color: #15803d; }
        .status-pill.warning { background: #ffedd5; color: #c2410c; }

        .assessment-content {
            display: grid;
            grid-template-columns: 2.1fr 1fr;
            gap: 20px;
        }

        .card-header h4 {
            font-weight: 700;
            font-size: 1rem;
        }

        .card-header p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .instrument-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .instrument-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 14px;
            display: grid;
            gap: 10px;
            background: #fff;
        }

        .instrument-card h5 {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .instrument-card p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .instrument-card button {
            margin-top: auto;
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 0.75rem;
            color: #256c32;
        }

        .instrument-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #256c32;
            background: #e5f6e5;
        }

        .instrument-card.blue .instrument-icon { background: #dbeafe; color: #1d4ed8; }
        .instrument-card.purple .instrument-icon { background: #ede9fe; color: #6d28d9; }

        .table-wrap {
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .history-table th,
        .history-table td {
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
            color: var(--muted);
        }

        .history-table th {
            color: #475569;
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
        }

        .pill {
            display: inline-flex;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .pill.green { background: #dcfce7; color: #15803d; }
        .pill.blue { background: #dbeafe; color: #1d4ed8; }
        .pill.purple { background: #ede9fe; color: #6d28d9; }
        .pill.success { background: #dcfce7; color: #15803d; }

        .ghost-button {
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .info-card p {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .info-list {
            background: #f0fdf4;
            border-radius: 14px;
            padding: 12px;
            display: grid;
            gap: 8px;
        }

        .info-list h5 {
            font-weight: 600;
            font-size: 0.8rem;
            color: #166534;
        }

        .info-list ul {
            list-style: disc;
            margin-left: 16px;
            font-size: 0.75rem;
            color: #166534;
            display: grid;
            gap: 6px;
        }

        .quote-card {
            background: #f0fdf4;
            color: #166534;
            font-size: 0.8rem;
            text-align: center;
        }

        .quote-card span {
            display: block;
            margin-top: 6px;
            font-size: 0.7rem;
            color: #15803d;
        }

        .accordion {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: var(--shadow);
        }

        .accordion summary {
            cursor: pointer;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-grid {
            display: grid;
            gap: 16px;
            margin-top: 16px;
        }

        .form-row {
            display: grid;
            gap: 8px;
        }

        .form-row label {
            font-size: 0.85rem;
            color: var(--muted);
        }

        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
        }

        .primary-button {
            background: #4f9b4f;
            color: #fff;
            border: none;
            padding: 10px 16px;
            border-radius: 12px;
            font-weight: 600;
            width: fit-content;
        }

        .small {
            font-size: 0.75rem;
        }

        @media (max-width: 1200px) {
            .assessment-hero {
                grid-template-columns: 1fr;
            }

            .hero-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .assessment-content {
                grid-template-columns: 1fr;
            }

            .instrument-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .dashboard-sidebar {
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

            .nav-group {
                min-width: 180px;
            }
        }
    </style>
</x-app-layout>
