@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $patientName = Auth::user()->name ?? 'Pasien';
    $patientAge = '62 Tahun';
    $patientGender = 'Laki-laki';
    $patientRoom = 'Ruang: Mawar 3';
    $patientRm = 'No. RM: 23051567';
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

    $activityCards = [
        [
            'title' => 'Doa Pagi',
            'description' => $recommendations[0] ?? 'Mulai hari dengan doa dan memohon ketenangan.',
            'tone' => 'green',
        ],
        [
            'title' => 'Dzikir & Istighfar',
            'description' => $recommendations[1] ?? 'Bawa ketenangan dengan dzikir singkat.',
            'tone' => 'blue',
        ],
        [
            'title' => 'Refleksi Diri',
            'description' => $recommendations[2] ?? 'Luangkan waktu untuk mensyukuri hari ini.',
            'tone' => 'purple',
        ],
        [
            'title' => 'Istirahat Tenang',
            'description' => 'Ambil waktu untuk menenangkan pikiran dan tubuh.',
            'tone' => 'leaf',
        ],
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
                    <div class="alert success">{{ __('Cek harian tersimpan.') }}</div>
                @endif
                @if (session('status') === 'journal-saved')
                    <div class="alert info">{{ __('Catatan reflektif tersimpan.') }}</div>
                @endif
                @if (! $hasToday)
                    <div class="alert warning">
                        <span>{{ __('Anda belum mengisi cek harian hari ini.') }}</span>
                        <a href="#daily-check">{{ __('Isi sekarang') }}</a>
                    </div>
                @endif
                @if ($errors->has('daily'))
                    <div class="alert warning">{{ $errors->first('daily') }}</div>
                @endif
                @if ($errors->has('journal'))
                    <div class="alert warning">{{ $errors->first('journal') }}</div>
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
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 7h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7zm2 4h4v4H6z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Hari Ini</p>
                            <p class="stat-value">{{ $todayDate }}</p>
                            <p class="stat-sub">{{ $todayDayName }}</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12zm0 18a5 5 0 0 1-5-5c0-2.9 1.7-5.7 5-9 3.3 3.3 5 6.1 5 9a5 5 0 0 1-5 5z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Kondisi Umum</p>
                            <span class="status-pill {{ $overallStatus['tone'] }}">{{ $overallStatus['label'] }}</span>
                            <p class="stat-sub">Diperbarui: {{ now()->format('H:i') }} WIB</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h11a3 3 0 0 1 3 3v13H8a3 3 0 0 0-3 3V4z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Aktivitas Rohani Hari Ini</p>
                            <p class="stat-value">{{ $activityHighlight }}</p>
                            <a class="stat-link" href="#daily-check">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21c0-6 3-11 9-13-1 7-5 12-9 13zm0 0C6 19 3 14 3 8c6 2 9 7 9 13z" fill="currentColor"/></svg>
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
                        <button class="ghost-button" type="button">Lihat Detail</button>
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
                        <button class="ghost-button" type="button">Lihat Detail</button>
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
                        <button class="ghost-button" type="button">Lihat Detail</button>
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
                        <button class="ghost-button" type="button">Lihat Semua</button>
                    </div>
                    <div class="activity-grid">
                        @foreach ($activityCards as $activity)
                            <div class="activity-card {{ $activity['tone'] }}">
                                <div class="activity-icon"></div>
                                <h5>{{ $activity['title'] }}</h5>
                                <p>{{ $activity['description'] }}</p>
                                <button type="button">Mulai</button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card tips-card">
                    <div class="card-header">
                        <div>
                            <h4>Tips Hari Ini</h4>
                            <p>Jaga hati, jaga ketenangan</p>
                        </div>
                    </div>
                    <div class="tips-visual"></div>
                    <p class="tips-text">
                        Ketika hati tenang, tubuh pun ikut merasakan kedamaian.
                        Tarik napas dalam, hembuskan pelan, serahkan semua pada-Nya.
                    </p>
                    <button class="ghost-button" type="button">Baca Selengkapnya</button>
                </div>
            </section>

            <section class="footer-note">
                <div>
                    <strong>Teruslah merawat ruh dengan kebaikan setiap hari.</strong>
                    <p>Perjalanan spiritual adalah proses, bukan tujuan. Setiap langkah kecil adalah kemajuan.</p>
                </div>
                <span class="leaf-mark"></span>
            </section>

            <section class="extras" id="daily-check">
                <details class="accordion">
                    <summary>Isi Cek Harian</summary>
                    @if ($hasToday)
                        <p class="muted">Anda sudah mengisi cek harian hari ini.</p>
                    @else
                        <form method="POST" action="{{ route('dashboard.radar.store') }}" class="form-grid">
                            @csrf

                            @php
                                $questions = [
                                    'score_meaning' => 'Makna hidup',
                                    'score_closeness' => 'Kedekatan dengan Tuhan/yang Ilahi',
                                    'score_peace' => 'Rasa damai',
                                    'score_fear' => 'Rasa takut terhadap masa depan/kematian',
                                    'score_loneliness' => 'Rasa kesepian',
                                ];
                                $esasQuestions = [
                                    'symptom_pain' => 'Nyeri',
                                    'symptom_fatigue' => 'Lelah',
                                    'symptom_nausea' => 'Mual',
                                    'symptom_anxiety' => 'Cemas',
                                    'symptom_sadness' => 'Sedih',
                                ];
                            @endphp

                            <div class="form-section">
                                <h5>Skala Spiritual (1-5)</h5>
                                @foreach ($questions as $field => $label)
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
                            </div>

                            <div class="form-section">
                                <h5>Skala Gejala ESAS (1-5)</h5>
                                @foreach ($esasQuestions as $field => $label)
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

                                <div class="form-row">
                                    <label>Gejala/keluhan utama (opsional)</label>
                                    <textarea name="symptoms" rows="3">{{ old('symptoms') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('symptoms')" />
                                </div>
                            </div>

                            <button type="submit" class="primary-button">Simpan</button>
                        </form>
                    @endif
                </details>

                <details class="accordion" id="journal">
                    <summary>Jurnal Reflektif</summary>
                    @if ($hasJournalToday)
                        <p class="muted">Catatan reflektif hari ini sudah dibuat.</p>
                    @else
                        <form method="POST" action="{{ route('journals.store') }}" class="form-grid">
                            @csrf
                            <div class="form-row">
                                <label>Catatan hari ini</label>
                                <textarea name="content" rows="4">{{ old('content') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>
                            <label class="checkbox-row">
                                <input type="checkbox" name="is_shareable" value="1" @checked(old('is_shareable'))>
                                <span>Izinkan tenaga kesehatan membaca catatan ini</span>
                            </label>
                            <button type="submit" class="primary-button">Simpan catatan</button>
                        </form>
                    @endif

                    <div class="journal-list">
                        @foreach ($journalEntries as $entry)
                            <div class="journal-card">
                                <span class="date">{{ $entry->entry_date->format('d M Y') }}</span>
                                <p>{{ $entry->content }}</p>
                                @if ($entry->provider_response)
                                    <div class="response">
                                        <strong>Respon tenaga kesehatan</strong>
                                        <p>{{ $entry->provider_response }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </details>
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

        .activity-card button {
            align-self: flex-start;
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
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

        .extras {
            display: grid;
            gap: 16px;
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

        .form-section {
            display: grid;
            gap: 12px;
        }

        .form-section h5 {
            font-weight: 600;
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

        .checkbox-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .form-row textarea,
        .form-grid textarea {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 10px;
            font-size: 0.85rem;
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

        .journal-list {
            display: grid;
            gap: 12px;
            margin-top: 16px;
        }

        .journal-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .journal-card .date {
            font-size: 0.7rem;
        }

        .journal-card .response {
            margin-top: 10px;
            background: #f8fafc;
            padding: 10px;
            border-radius: 10px;
            color: #1f2937;
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
