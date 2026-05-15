@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $_user = Auth::user();
    $patientName = $_user->name ?? 'Pasien';
    $patientAge = $_user->patient_age ? $_user->patient_age . ' Tahun' : '--';
    $patientGender = $_user->patient_gender ?? '--';
    $patientRm = $_user->patient_rm ? 'No. RM: ' . $_user->patient_rm : '--';
    $patientRoom = $_user->patient_room ? 'Ruang: ' . $_user->patient_room : '--';
    $spiritualScoreLabel = $spiritualScoreToday !== null ? $spiritualScoreToday . '/100' : '--';
    $emotionLabel = $emotionScoreToday !== null ? number_format($emotionScoreToday, 1) . '/5' : '--';

    $emotionText = 'Belum ada data';
    if ($emotionScoreToday !== null) {
        if ($emotionScoreToday >= 4) {
            $emotionText = 'Tenang';
        } elseif ($emotionScoreToday >= 3) {
            $emotionText = 'Stabil';
        } else {
            $emotionText = 'Perlu dukungan';
        }
    }

    $activityDefs = [
        ['key' => 'doa', 'title' => 'Doa Pagi', 'tone' => 'green', 'default' => 'Mulai hari dengan doa dan memohon ketenangan.'],
        ['key' => 'dzikir', 'title' => 'Dzikir & Istighfar', 'tone' => 'blue', 'default' => 'Bawa ketenangan dengan dzikir singkat.'],
        ['key' => 'refleksi', 'title' => 'Refleksi Diri', 'tone' => 'purple', 'default' => 'Luangkan waktu untuk merefleksikan perasaan hari ini.'],
        ['key' => 'istirahat', 'title' => 'Istirahat Tenang', 'tone' => 'leaf', 'default' => 'Ambil waktu untuk menenangkan pikiran dan tubuh.'],
    ];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen dashboard-page">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="dashboard-main">
            <x-app-topbar
                class="dashboard-topbar"
                title="Dashboard Spiritual Harian"
                subtitle="Terapi Rohani Pasien Paliatif"
                :badgeCount="! $hasToday ? 1 : 0"
            />

            <div class="dashboard-alerts">
                @if (session('status') === 'radar-saved')
                    <div class="alert success">{{ __('Data tersimpan.') }}</div>
                @endif
                @if (session('status') === 'journal-saved')
                    <div class="alert info">{{ __('Catatan tersimpan.') }}</div>
                @endif
            </div>

            <section class="hero-card">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
                        <p class="muted">{{ $patientRm }} - {{ $patientRoom }}</p>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v3M16 2v3M3 7h18M5 5h14a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/><path d="M3 11h18"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Hari Ini</p>
                            <p class="stat-value">{{ $todayDate }}</p>
                            <p class="stat-sub">{{ $todayDayName }}</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Kondisi Umum</p>
                            <span class="status-pill {{ $overallStatus['tone'] }}">{{ $overallStatus['label'] }}</span>
                            <p class="stat-sub">Diperbarui: {{ now()->format('H:i') }} WIB</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Aktivitas Rohani Hari Ini</p>
                            <p class="stat-value">{{ $activityHighlight }}</p>
                            <a class="stat-link" href="{{ route('menu.emotional-evaluation') }}">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Konsistensi</p>
                            <p class="stat-value">{{ $streak }} hari berturut-turut</p>
                            <p class="stat-sub">Pertahankan semangat!</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid-row grid-3">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4>Skor Spiritual Harian</h4>
                            <p>Skor spiritual Anda dalam {{ $range }} hari terakhir</p>
                        </div>
                        <a href="{{ route('dashboard') }}?range=30" class="ghost-button">Lihat Detail</a>
                    </div>
                    <div class="chart-wrap">
                        <canvas id="spiritualScoreChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4>Tingkat Gejala (ESAS)</h4>
                            <p>Rata-rata tingkat gejala Anda</p>
                        </div>
                        <a href="{{ route('menu.assessment') }}" class="ghost-button">Lihat Detail</a>
                    </div>
                    <div class="esas-list">
                        @foreach ($esasScores as $esas)
                            <div class="esas-item">
                                <div class="esas-label">{{ $esas['label'] }}</div>
                                <div class="esas-bar">
                                    <span style="width: {{ ($esas['value'] ?? 0) * 20 }}%"></span>
                                </div>
                                <div class="esas-value">{{ $esas['value'] ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4>Tren Emosi</h4>
                            <p>Bagaimana perasaan Anda dari hari ke hari</p>
                        </div>
                        <a href="{{ route('menu.emotional-evaluation') }}" class="ghost-button">Lihat Detail</a>
                    </div>
                    <div class="emotion-chart">
                        <div class="emotion-legend">
                            <span class="emotion-dot good" title="Sangat baik"></span>
                            <span class="emotion-dot calm" title="Baik"></span>
                            <span class="emotion-dot neutral" title="Netral"></span>
                            <span class="emotion-dot tense" title="Cemas"></span>
                            <span class="emotion-dot sad" title="Sedih"></span>
                        </div>
                        <div class="chart-wrap">
                            <canvas id="emotionTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid-row grid-3">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4>Ringkasan Hari Ini</h4>
                            <p>{{ $todayDayName }}, {{ $todayDate }}</p>
                        </div>
                    </div>
                    <div class="summary-quote">
                        <p>"Sesungguhnya bersama kesulitan ada kemudahan."</p>
                        <span>(QS. Al-Insyirah: 6)</span>
                    </div>
                    <div class="summary-list">
                        <div>
                            <span>Skor Spiritual</span>
                            <strong>{{ $spiritualScoreLabel }}</strong>
                        </div>
                        <div>
                            <span>Kondisi Emosi</span>
                            <strong>{{ $emotionText }}</strong>
                        </div>
                        <div>
                            <span>Aktivitas Rohani</span>
                            <strong>{{ $activityHighlight }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4>Aktivitas Rohani yang Disarankan</h4>
                            <p>Pilih aktivitas yang sesuai dengan kondisi Anda saat ini</p>
                        </div>
                        <a href="{{ route('education.index') }}" class="ghost-button">Lihat Semua</a>
                    </div>
                    <div class="activity-grid">
                        @foreach ($activityDefs as $def)
                            @php
                                $module = $recommendedActivities[$def['key']] ?? null;
                                $desc = $module ? $module->summary : $def['default'];
                                $route = $module ? route('education.show', $module) : route('education.index');
                            @endphp
                            <div class="activity-card {{ $def['tone'] }}">
                                <div class="activity-icon"></div>
                                <h5>{{ $def['title'] }}</h5>
                                <p>{{ $desc }}</p>
                                <a href="{{ $route }}" class="activity-btn">Mulai</a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card tips-card">
                    <div class="card-header">
                        <div>
                            <h4>Tips Hari Ini</h4>
                            <p>Dari modul edukasi untuk Anda</p>
                        </div>
                    </div>
                    <div class="tips-visual"></div>
                    @if ($dailyTip)
                        <p class="tips-text">{{ $dailyTip->summary }}</p>
                        <a href="{{ route('education.index') }}" class="ghost-button">Baca Selengkapnya</a>
                    @else
                        <p class="tips-text">Luangkan waktu sejenak untuk berdoa, berdzikir, atau merenung sesuai keyakinan Anda.</p>
                        <a href="{{ route('education.index') }}" class="ghost-button">Baca Selengkapnya</a>
                    @endif
                </div>
            </section>

            <section class="footer-note">
                <div>
                    <strong>Teruslah merawat ruh dengan kebaikan setiap hari.</strong>
                    <p>Perjalanan spiritual adalah proses, bukan tujuan. Setiap langkah kecil adalah kemajuan.</p>
                </div>
                <span class="leaf-mark"></span>
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
            gap: 8px;
        }

        .dashboard-topbar {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .dashboard-topbar > div:first-child {
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

        .dashboard-alerts {
            display: grid;
            gap: 10px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            background: #fff7ed;
            color: #92400e;
            font-size: 0.85rem;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .alert.success {
            background: #ecfdf3;
            color: #15803d;
        }

        .alert.info {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .alert a {
            font-weight: 600;
            color: inherit;
        }

        .hero-card {
            display: grid;
            grid-template-columns: 1.2fr 3fr;
            gap: 20px;
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--shadow);
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

        .patient-profile h3 {
            font-weight: 700;
        }

        .muted {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .stat-card {
            background: #f9fafb;
            border-radius: 16px;
            padding: 12px;
            display: grid;
            gap: 8px;
            grid-template-columns: auto 1fr;
            align-items: center;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: #e5f6e5;
            display: grid;
            place-items: center;
            color: #3f7a3f;
        }

        .stat-icon svg {
            width: 18px;
            height: 18px;
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .stat-value {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text);
        }

        .stat-sub {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .stat-link {
            font-size: 0.75rem;
            color: #2f855a;
        }

        .status-pill {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .grid-row {
            display: grid;
            gap: 20px;
        }

        .grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .card {
            background: var(--surface);
            border-radius: 20px;
            padding: 18px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
        }

        .card-header h4 {
            font-weight: 700;
            font-size: 1rem;
        }

        .card-header p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .ghost-button {
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .chart-wrap {
            height: 200px;
        }

        .esas-list {
            display: grid;
            gap: 10px;
        }

        .esas-item {
            display: grid;
            grid-template-columns: 80px 1fr 24px;
            gap: 10px;
            align-items: center;
            font-size: 0.8rem;
        }

        .esas-bar {
            height: 8px;
            border-radius: 999px;
            background: #edf2f7;
            position: relative;
        }

        .esas-bar span {
            position: absolute;
            inset: 0;
            width: 0;
            background: #4f9b4f;
            border-radius: 999px;
        }

        .emotion-chart {
            display: grid;
            gap: 10px;
        }

        .emotion-legend {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .emotion-dot {
            width: 16px;
            height: 16px;
            border-radius: 999px;
            display: inline-block;
        }

        .emotion-dot.good { background: #22c55e; }
        .emotion-dot.calm { background: #86efac; }
        .emotion-dot.neutral { background: #fde047; }
        .emotion-dot.tense { background: #f97316; }
        .emotion-dot.sad { background: #ef4444; }

        .summary-quote {
            background: #eef7ee;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            color: #2f855a;
        }

        .summary-list {
            display: grid;
            gap: 10px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .summary-list div {
            display: flex;
            justify-content: space-between;
            padding-bottom: 6px;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .activity-card {
            border-radius: 16px;
            padding: 14px;
            border: 1px solid #e2e8f0;
            background: #fff;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .activity-icon {
            width: 28px;
            height: 28px;
            border-radius: 10px;
            background: #e2e8f0;
        }

        .activity-card h5 {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .activity-card p {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .activity-btn {
            align-self: flex-start;
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            text-decoration: none;
            color: #475569;
        }

        .activity-card.green { border-color: #cfead5; }
        .activity-card.blue { border-color: #dbeafe; }
        .activity-card.purple { border-color: #ede9fe; }
        .activity-card.leaf { border-color: #d1fae5; }

        .activity-card.green .activity-icon { background: #dcfce7; }
        .activity-card.blue .activity-icon { background: #dbeafe; }
        .activity-card.purple .activity-icon { background: #ede9fe; }
        .activity-card.leaf .activity-icon { background: #d1fae5; }

        .tips-card {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .tips-visual {
            height: 120px;
            border-radius: 14px;
            background: linear-gradient(135deg, #fef3c7, #fcd34d, #86efac);
        }

        .tips-text {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.4;
        }

        .footer-note {
            background: #f0fdf4;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #166534;
        }

        .leaf-mark {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: #bbf7d0;
            display: inline-block;
        }

        @media (max-width: 1200px) {
            .dashboard-topbar {
                flex-direction: column;
                align-items: flex-start;
                justify-content: space-between;
            }

            .dashboard-topbar > div:first-child {
                text-align: left;
            }

            .dashboard-topbar .topbar-actions {
                position: static;
            }
            .hero-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .grid-3 {
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
            }

            .nav-item {
                white-space: nowrap;
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        const scoreLabels = @json($chartLabels);
        const scoreTrend = @json($scoreTrend);
        const emotionTrend = @json($emotionTrend);

        const scoreCtx = document.getElementById('spiritualScoreChart');
        if (scoreCtx) {
            new Chart(scoreCtx, {
                type: 'line',
                data: {
                    labels: scoreLabels,
                    datasets: [
                        {
                            label: 'Skor Spiritual',
                            data: scoreTrend,
                            borderColor: '#5aa85a',
                            backgroundColor: 'rgba(90, 168, 90, 0.12)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#5aa85a',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            ticks: {
                                stepSize: 20,
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        }

        const emotionCtx = document.getElementById('emotionTrendChart');
        if (emotionCtx) {
            new Chart(emotionCtx, {
                type: 'line',
                data: {
                    labels: scoreLabels,
                    datasets: [
                        {
                            label: 'Emosi',
                            data: emotionTrend,
                            borderColor: '#60a5fa',
                            backgroundColor: 'rgba(96, 165, 250, 0.15)',
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#60a5fa',
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 1,
                            max: 5,
                            ticks: {
                                stepSize: 1,
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                },
            });
        }
    </script>
</x-app-layout>
