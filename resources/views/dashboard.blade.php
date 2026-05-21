@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
@endpush

@php
    $_user = Auth::user();
    $patientName = $_user->name ?? 'Pasien';
    $patientAge = $_user->patient_age ? $_user->patient_age . ' Tahun' : '--';
    $patientGender = $_user->patient_gender ?? '--';

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

    $toneColors = ['green', 'blue', 'purple', 'leaf'];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen dashboard-page">
    <div class="dashboard-layout" x-data="dashboardState()">
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
                @if (session('status') === 'verification-link-sent')
                    <div class="alert success">{{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}</div>
                @endif
            </div>

            @if (! Auth::user()->hasVerifiedEmail())
                <div class="verify-banner">
                    <div class="verify-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div class="verify-body">
                        <strong>Email Anda belum terverifikasi.</strong>
                        <p>Silakan periksa kotak masuk email Anda dan klik tautan verifikasi. Beberapa fitur mungkin terbatas.</p>
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="verify-resend-btn">Kirim Ulang</button>
                    </form>
                </div>
            @endif

            @if (! $hasToday)
                <section class="checkin-prompt" x-data="{ open: true }" x-show="open">
                    <div class="checkin-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    </div>
                    <div class="checkin-body">
                        <h4>Belum Mengisi Cek Harian</h4>
                        <p>Pantau kondisi spiritual dan fisik Anda hari ini dengan mengisi cek harian.</p>
                        <button class="primary-button" @click="open = false; document.getElementById('checkinModal').classList.add('show')">
                            Isi Cek Harian
                        </button>
                    </div>
                    <button class="checkin-dismiss" @click="open = false">&times;</button>
                </section>

                <div class="modal-overlay" x-data x-ref="checkinModal" id="checkinModal" @click.self="document.getElementById('checkinModal').classList.remove('show')">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Cek Harian Spiritual</h3>
                            <p>Nilailah kondisi spiritual dan fisik Anda hari ini (skala 1–5)</p>
                            <button class="modal-close" @click="document.getElementById('checkinModal').classList.remove('show')" type="button">&times;</button>
                        </div>
                        <form method="POST" action="{{ route('dashboard.radar.store') }}">
                            @csrf
                            @if ($errors->has('daily'))
                                <div class="alert warning">{{ $errors->first('daily') }}</div>
                            @endif

                            <div class="checkin-section">
                                <h4>Kondisi Spiritual</h4>
                                <p class="section-desc">Seberapa Anda merasakan hal-hal berikut <strong>hari ini</strong>?</p>

                                @php
                                    $spiritualDims = [
                                        ['id' => 'score_meaning', 'label' => 'Makna Hidup', 'desc' => 'Merasa hidup hari ini bermakna'],
                                        ['id' => 'score_closeness', 'label' => 'Kedekatan dengan Tuhan', 'desc' => 'Merasa dekat dengan Tuhan/Yang Ilahi'],
                                        ['id' => 'score_peace', 'label' => 'Rasa Damai', 'desc' => 'Merasa tenang dan damai'],
                                        ['id' => 'score_fear', 'label' => 'Rasa Takut', 'desc' => 'Merasa cemas atau takut'],
                                        ['id' => 'score_loneliness', 'label' => 'Rasa Kesepian', 'desc' => 'Merasa sendiri atau terisolasi'],
                                    ];
                                @endphp

                                @foreach ($spiritualDims as $dim)
                                    <div class="checkin-field">
                                        <label>{{ $dim['label'] }}</label>
                                        <p class="field-desc">{{ $dim['desc'] }}</p>
                                        <div class="scale-wrapper">
                                            <div class="scale-group spiritual-scale">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <label class="scale-option">
                                                        <input type="radio" name="{{ $dim['id'] }}" value="{{ $i }}" required>
                                                        <span class="scale-num">{{ $i }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                            <div class="scale-labels">
                                                <span class="scale-label-start">Sangat Tidak Setuju</span>
                                                <span class="scale-label-end">Sangat Setuju</span>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get($dim['id'])" class="mt-1" />
                                    </div>
                                @endforeach
                            </div>

                            <div class="checkin-section">
                                <h4>Gejala Fisik</h4>
                                <p class="section-desc">Seberapa berat gejala berikut Anda rasakan <strong>hari ini</strong>?</p>

                                @php
                                    $symptomDims = [
                                        ['id' => 'symptom_pain', 'label' => 'Nyeri'],
                                        ['id' => 'symptom_fatigue', 'label' => 'Lelah'],
                                        ['id' => 'symptom_nausea', 'label' => 'Mual'],
                                        ['id' => 'symptom_anxiety', 'label' => 'Cemas'],
                                        ['id' => 'symptom_sadness', 'label' => 'Sedih'],
                                    ];
                                @endphp

                                @foreach ($symptomDims as $dim)
                                    <div class="checkin-field">
                                        <label>{{ $dim['label'] }}</label>
                                        <div class="scale-wrapper">
                                            <div class="scale-group symptom-scale">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <label class="scale-option">
                                                        <input type="radio" name="{{ $dim['id'] }}" value="{{ $i }}" required>
                                                        <span class="scale-num">{{ $i }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                            <div class="scale-labels">
                                                <span class="scale-label-start">Tidak Ada</span>
                                                <span class="scale-label-end">Sangat Berat</span>
                                            </div>
                                        </div>
                                        <x-input-error :messages="$errors->get($dim['id'])" class="mt-1" />
                                    </div>
                                @endforeach
                            </div>

                            <div class="checkin-section">
                                <h4>Gejala Lainnya (Opsional)</h4>
                                <textarea name="symptoms" rows="2" placeholder="Ceritakan keluhan lain yang Anda rasakan...">{{ old('symptoms') }}</textarea>
                                @if ($errors->has('symptoms'))
                                    <x-input-error :messages="$errors->get('symptoms')" class="mt-1" />
                                @endif
                            </div>

                            <div class="checkin-footer">
                                <p class="checkin-disclaimer">Cek harian ini bersifat saring (screening) dan bukan diagnosis medis.</p>
                                <div class="checkin-actions">
                                    <button type="button" class="ghost-button" @click="document.getElementById('checkinModal').classList.remove('show')">Nanti Saja</button>
                                    <button type="submit" class="primary-button">Simpan Cek Harian</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <section class="hero-card">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
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
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        </div>
                        <div>
                            <p class="stat-label">Aktivitas Rohani</p>
                            <p class="stat-value stat-value-sm">{{ $activityHighlight }}</p>
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
                        <button type="button" class="ghost-button" @click="openSpiritualModal = true; $nextTick(() => updateSpiritualChart())">Lihat Detail</button>
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
                                    <span style="width: {{ ($esas['value'] ?? 0) * 10 }}%"></span>
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
                        <button type="button" class="ghost-button" @click="openEmotionModal = true; $nextTick(() => updateEmotionChart())">Lihat Detail</button>
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
                <div class="card summary-card">
                    <div class="card-header">
                        <div>
                            <h4>Ringkasan Hari Ini</h4>
                            <p>{{ $todayDayName }}, {{ $todayDate }}</p>
                        </div>
                    </div>
                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </div>
                            <div class="summary-body">
                                <span class="summary-label">Skor Spiritual</span>
                                <span class="summary-value">{{ $spiritualScoreLabel }}</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon calm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><circle cx="9" cy="9" r="1"/><circle cx="15" cy="9" r="1"/></svg>
                            </div>
                            <div class="summary-body">
                                <span class="summary-label">Kondisi Emosi</span>
                                <span class="summary-value">{{ $emotionText }}</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon journal">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 10h16M4 14h12M4 18h8"/></svg>
                            </div>
                            <div class="summary-body">
                                <span class="summary-label">Jurnal Hari Ini</span>
                                <span class="summary-value">{{ $todayJournals }} entri</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon prayer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8-4-8-10c0-4 3.5-6 8-6s8 2 8 6c0 6-8 10-8 10z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                            </div>
                            <div class="summary-body">
                                <span class="summary-label">Doa Terkirim</span>
                                <span class="summary-value">{{ $totalPrayers }} doa</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon tree">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8-4-8-10c0-4 3.5-6 8-6s8 2 8 6c0 6-8 10-8 10z M12 6v10 M9 9l3 3 3-3"/></svg>
                            </div>
                            <div class="summary-body">
                                <span class="summary-label">Pohon Doa</span>
                                <span class="summary-value">Tahap {{ $treeStage }}/7</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon {{ $hasSwbs && $hasEcog && $hasEsas ? 'assessment-done' : 'assessment' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2 M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"/><path d="M9 13l2 2 4-4"/></svg>
                            </div>
                            <div class="summary-body">
                                <span class="summary-label">Pengkajian</span>
                                <span class="summary-value">{{ $hasSwbs && $hasEcog && $hasEsas ? 'Lengkap' : 'Belum Lengkap' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($highlightedModules->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h4>Aktivitas Rohani yang Disarankan</h4>
                            <p>Modul edukasi pilihan untuk kondisi Anda</p>
                        </div>
                        <a href="{{ route('education.index') }}" class="ghost-button">Lihat Semua</a>
                    </div>
                    <div class="activity-grid">
                        @foreach ($highlightedModules as $index => $module)
                            <div class="activity-card {{ $toneColors[$index % 4] }}">
                                <div class="activity-icon">
                                    @if ($module->type === 'video')
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                    @endif
                                </div>
                                <h5>{{ $module->title }}</h5>
                                <p>{{ Str::limit($module->summary, 80) }}</p>
                                <a href="{{ route('education.show', $module) }}" class="activity-btn">Mulai</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card tips-card-v2">
                    <div class="tips-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="tips-body">
                        <h5>Tahukah Anda?</h5>
                        <p class="tips-text">{{ $dailyTip['text'] }}</p>
                        <span class="tips-source">— {{ $dailyTip['source'] }}</span>
                    </div>
                </div>
            </section>

            <section class="footer-note">
                <span class="footer-leaf">🌿</span>
                <span>Teruslah merawat ruh dengan kebaikan setiap hari.</span>
            </section>

            <!-- Modal Detail Skor Spiritual -->
            <div class="modal-overlay" :class="{ 'show': openSpiritualModal }" @click.self="openSpiritualModal = false" x-cloak>
                <div class="modal-content modal-content-large">
                    <div class="modal-header">
                        <h3>Detail Skor Spiritual</h3>
                        <p>Analisis tren perkembangan spiritualitas Anda</p>
                        <button class="modal-close" @click="openSpiritualModal = false" type="button">&times;</button>
                    </div>
                    
                    <div class="stock-dashboard">
                        <div class="stock-metric-row">
                            <span class="stock-value" x-text="getStats('spiritual', spiritualTimeframe).current"></span>
                            <span class="stock-unit">/100</span>
                            <div class="stock-change" :class="getStats('spiritual', spiritualTimeframe).changeClass">
                                <span class="change-icon" x-text="getStats('spiritual', spiritualTimeframe).icon"></span>
                                <span class="change-text" x-text="getStats('spiritual', spiritualTimeframe).changeText"></span>
                                <span class="change-period">terhadap awal periode</span>
                            </div>
                        </div>
                        
                        <div class="timeframe-selector">
                            <button type="button" class="timeframe-btn" :class="{ 'active': spiritualTimeframe === '1W' }" @click="spiritualTimeframe = '1W'; updateSpiritualChart()">1M (Minggu)</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': spiritualTimeframe === '1M' }" @click="spiritualTimeframe = '1M'; updateSpiritualChart()">1B (Bulan)</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': spiritualTimeframe === '3M' }" @click="spiritualTimeframe = '3M'; updateSpiritualChart()">3B</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': spiritualTimeframe === '6M' }" @click="spiritualTimeframe = '6M'; updateSpiritualChart()">6B</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': spiritualTimeframe === '1Y' }" @click="spiritualTimeframe = '1Y'; updateSpiritualChart()">1T (Tahun)</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': spiritualTimeframe === 'ALL' }" @click="spiritualTimeframe = 'ALL'; updateSpiritualChart()">Semua</button>
                        </div>
                    </div>
                    
                    <div class="modal-chart-wrap">
                        <canvas id="modalSpiritualChart"></canvas>
                    </div>
                    
                    <div class="modal-footer-info">
                        <p>💡 <strong>Tips:</strong> Skor spiritual Anda dihitung secara komprehensif dari makna hidup, rasa damai, dan kedekatan spiritual Anda sehari-hari.</p>
                    </div>
                </div>
            </div>

            <!-- Modal Detail Tren Emosi -->
            <div class="modal-overlay" :class="{ 'show': openEmotionModal }" @click.self="openEmotionModal = false" x-cloak>
                <div class="modal-content modal-content-large">
                    <div class="modal-header">
                        <h3>Detail Tren Emosi</h3>
                        <p>Analisis kondisi emosi dan suasana hati Anda</p>
                        <button class="modal-close" @click="openEmotionModal = false" type="button">&times;</button>
                    </div>
                    
                    <div class="stock-dashboard">
                        <div class="stock-metric-row">
                            <span class="stock-value" x-text="typeof getStats('emotion', emotionTimeframe).current === 'number' ? getStats('emotion', emotionTimeframe).current.toFixed(1) : getStats('emotion', emotionTimeframe).current"></span>
                            <span class="stock-unit">/5</span>
                            <div class="stock-change" :class="getStats('emotion', emotionTimeframe).changeClass">
                                <span class="change-icon" x-text="getStats('emotion', emotionTimeframe).icon"></span>
                                <span class="change-text" x-text="getStats('emotion', emotionTimeframe).changeText"></span>
                                <span class="change-period">terhadap awal periode</span>
                            </div>
                        </div>
                        
                        <div class="timeframe-selector">
                            <button type="button" class="timeframe-btn" :class="{ 'active': emotionTimeframe === '1W' }" @click="emotionTimeframe = '1W'; updateEmotionChart()">1M (Minggu)</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': emotionTimeframe === '1M' }" @click="emotionTimeframe = '1M'; updateEmotionChart()">1B (Bulan)</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': emotionTimeframe === '3M' }" @click="emotionTimeframe = '3M'; updateEmotionChart()">3B</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': emotionTimeframe === '6M' }" @click="emotionTimeframe = '6M'; updateEmotionChart()">6B</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': emotionTimeframe === '1Y' }" @click="emotionTimeframe = '1Y'; updateEmotionChart()">1T (Tahun)</button>
                            <button type="button" class="timeframe-btn" :class="{ 'active': emotionTimeframe === 'ALL' }" @click="emotionTimeframe = 'ALL'; updateEmotionChart()">Semua</button>
                        </div>
                    </div>
                    
                    <div class="modal-chart-wrap">
                        <canvas id="modalEmotionChart"></canvas>
                    </div>
                    
                    <div class="modal-footer-info">
                        <p>💡 <strong>Tips:</strong> Tren emosi Anda didasarkan pada skala 1-5 (Sedih, Cemas, Netral, Baik, Sangat Baik) yang Anda isi pada cek harian.</p>
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
            grid-template-columns: repeat(3, minmax(0, 1fr));
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

        .stat-value-sm {
            font-size: 0.75rem;
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
            align-items: center;
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: normal;
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

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .summary-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #e5f6e5;
            display: grid;
            place-items: center;
            color: #3f7a3f;
            flex-shrink: 0;
        }

        .summary-icon svg {
            width: 17px;
            height: 17px;
        }

        .summary-icon.calm { background: #dbeafe; color: #1d4ed8; }
        .summary-icon.journal { background: #ede9fe; color: #6d28d9; }
        .summary-icon.prayer { background: #fef3c7; color: #b45309; }
        .summary-icon.tree { background: #d1fae5; color: #059669; }
        .summary-icon.assessment { background: #fee2e2; color: #b91c1c; }
        .summary-icon.assessment-done { background: #dcfce7; color: #15803d; }

        .summary-body {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .summary-label {
            font-size: 0.68rem;
            color: var(--muted);
        }

        .summary-value {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
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
            display: grid;
            place-items: center;
        }

        .activity-icon svg {
            width: 16px;
            height: 16px;
            color: #475569;
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
        .activity-card.green .activity-icon svg { color: #16a34a; }
        .activity-card.blue .activity-icon { background: #dbeafe; }
        .activity-card.blue .activity-icon svg { color: #2563eb; }
        .activity-card.purple .activity-icon { background: #ede9fe; }
        .activity-card.purple .activity-icon svg { color: #7c3aed; }
        .activity-card.leaf .activity-icon { background: #d1fae5; }
        .activity-card.leaf .activity-icon svg { color: #059669; }

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
            border-radius: 12px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #166534;
            font-weight: 500;
        }

        .footer-leaf {
            font-size: 1rem;
        }

        .tips-card-v2 {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 16px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            padding: 24px;
            height: 100%;
        }

        .tips-icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #fef3c7;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            color: #b45309;
        }

        .tips-icon-wrap svg {
            width: 18px;
            height: 18px;
        }

        .tips-body h5 {
            font-size: 0.82rem;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 4px;
        }

        .tips-source {
            font-size: 0.7rem;
            color: #b45309;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }
        .verify-banner {
            display: flex;
            align-items: center;
            gap: 14px;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
            border-radius: 14px;
            padding: 14px 20px;
            margin-bottom: 4px;
        }

        .verify-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #fef3c7;
            display: grid;
            place-items: center;
            color: #d97706;
            flex-shrink: 0;
        }

        .verify-icon svg {
            width: 20px;
            height: 20px;
        }

        .verify-body {
            flex: 1;
        }

        .verify-body strong {
            font-size: 0.85rem;
            color: #92400e;
        }

        .verify-body p {
            font-size: 0.78rem;
            color: #b45309;
            margin: 2px 0 0;
        }

        .verify-resend-btn {
            background: #f59e0b;
            color: #fff;
            border: none;
            padding: 7px 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.78rem;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease;
        }

        .verify-resend-btn:hover {
            background: #d97706;
        }

        .checkin-prompt {
            display: flex;
            align-items: center;
            gap: 16px;
            background: linear-gradient(135deg, #f0f9f0 0%, #e8f5e8 100%);
            border: 1px solid #c8e6c8;
            border-radius: 16px;
            padding: 18px 24px;
            margin-bottom: 16px;
        }

        .checkin-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #d4edda;
            display: grid;
            place-items: center;
            color: #2f855a;
            flex-shrink: 0;
        }

        .checkin-icon svg {
            width: 24px;
            height: 24px;
        }

        .checkin-body {
            flex: 1;
        }

        .checkin-body h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a4731;
            margin: 0 0 2px;
        }

        .checkin-body p {
            font-size: 0.8rem;
            color: #4a7a5a;
            margin: 0 0 8px;
        }

        .checkin-dismiss {
            border: none;
            background: none;
            font-size: 1.4rem;
            color: #6b9e7a;
            cursor: pointer;
            padding: 4px 8px;
            line-height: 1;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-content {
            background: #fff;
            border-radius: 24px;
            max-width: 680px;
            width: 100%;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.2);
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-header {
            margin-bottom: 24px;
            padding-right: 36px;
        }

        .modal-header h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 4px;
        }

        .modal-header p {
            font-size: 0.8rem;
            color: var(--muted);
            margin: 0;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            border: none;
            background: #f1f5f9;
            border-radius: 10px;
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--muted);
        }

        .modal-close:hover {
            background: #e2e8f0;
        }

        .checkin-section {
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .checkin-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .checkin-section h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #2d6a4f;
            margin: 0 0 4px;
        }

        .section-desc {
            font-size: 0.8rem;
            color: var(--muted);
            margin: 0 0 16px;
        }

        .checkin-field {
            margin-bottom: 18px;
        }

        .checkin-field label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 2px;
        }

        .field-desc {
            font-size: 0.75rem;
            color: var(--muted);
            margin: 0 0 8px;
        }

        .scale-group {
            display: flex;
            gap: 8px;
        }

        .scale-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        .scale-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .scale-num {
            display: grid;
            place-items: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #475569;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.15s;
            border: 2px solid transparent;
        }

        .scale-option:hover .scale-num {
            background: #e2e8f0;
        }

        .scale-wrapper {
            margin-top: 6px;
        }

        .scale-labels {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 262px;
            margin-top: 6px;
            padding: 0 4px;
        }

        .scale-labels span {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted);
        }

        .scale-label-start {
            text-align: left;
        }

        .scale-label-end {
            text-align: right;
        }

        /* Spiritual Scale checked colors */
        .spiritual-scale .scale-option input[value="1"]:checked + .scale-num { background: #fee2e2; border-color: #ef4444; color: #991b1b; }
        .spiritual-scale .scale-option input[value="2"]:checked + .scale-num { background: #ffedd5; border-color: #f97316; color: #9a3412; }
        .spiritual-scale .scale-option input[value="3"]:checked + .scale-num { background: #fef9c3; border-color: #eab308; color: #854d0e; }
        .spiritual-scale .scale-option input[value="4"]:checked + .scale-num { background: #e8f5e9; border-color: #4caf50; color: #1b5e20; }
        .spiritual-scale .scale-option input[value="5"]:checked + .scale-num { background: #d4edda; border-color: #4f9b4f; color: #2f855a; }

        /* Symptom Scale checked colors */
        .symptom-scale .scale-option input[value="1"]:checked + .scale-num { background: #d4edda; border-color: #4f9b4f; color: #2f855a; }
        .symptom-scale .scale-option input[value="2"]:checked + .scale-num { background: #e8f5e9; border-color: #8bc34a; color: #33691e; }
        .symptom-scale .scale-option input[value="3"]:checked + .scale-num { background: #fef9c3; border-color: #eab308; color: #854d0e; }
        .symptom-scale .scale-option input[value="4"]:checked + .scale-num { background: #ffedd5; border-color: #f97316; color: #9a3412; }
        .symptom-scale .scale-option input[value="5"]:checked + .scale-num { background: #fee2e2; border-color: #ef4444; color: #991b1b; }

        /* Stock-like Detail Modals styling */
        .modal-content-large {
            max-width: 800px;
        }

        .stock-dashboard {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            background: #f8fafc;
            padding: 16px 20px;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .stock-metric-row {
            display: flex;
            align-items: baseline;
            gap: 4px;
            flex-wrap: wrap;
        }

        .stock-value {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        .stock-unit {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--muted);
            margin-right: 12px;
        }

        .stock-change {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .stock-change.positive {
            background: #dcfce7;
            color: #15803d;
        }

        .stock-change.negative {
            background: #fee2e2;
            color: #b91c1c;
        }

        .stock-change.neutral {
            background: #f1f5f9;
            color: #475569;
        }

        .change-period {
            font-weight: 400;
            opacity: 0.85;
            margin-left: 2px;
            font-size: 0.72rem;
        }

        .timeframe-selector {
            display: flex;
            gap: 6px;
            background: #e2e8f0;
            padding: 4px;
            border-radius: 12px;
        }

        .timeframe-btn {
            border: none;
            background: transparent;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s;
        }

        .timeframe-btn:hover {
            background: rgba(255, 255, 255, 0.4);
            color: var(--text);
        }

        .timeframe-btn.active {
            background: #ffffff;
            color: var(--text);
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.05);
        }

        .modal-chart-wrap {
            height: 320px;
            margin-bottom: 20px;
            position: relative;
        }

        .modal-footer-info {
            background: #eff6ff;
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #bfdbfe;
        }

        .modal-footer-info p {
            font-size: 0.78rem;
            color: #1e3a8a;
            margin: 0;
            line-height: 1.5;
        }

        .checkin-section textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 10px 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            resize: vertical;
        }

        .checkin-section textarea:focus {
            outline: none;
            border-color: #4f9b4f;
            box-shadow: 0 0 0 3px rgba(79, 155, 79, 0.15);
        }

        .checkin-footer {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .checkin-disclaimer {
            font-size: 0.75rem;
            color: #94a3b8;
            margin: 0;
            font-style: italic;
        }

        .checkin-actions {
            display: flex;
            gap: 10px;
        }

        .primary-button {
            padding: 10px 24px;
            background: #4f9b4f;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            transition: background 0.15s;
        }

        .primary-button:hover {
            background: #3d8b3d;
        }

        @media (max-width: 1200px) {
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
        }

        @media (max-width: 640px) {
            .hero-stats {
                grid-template-columns: 1fr;
            }

            .patient-profile {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
            
            .dashboard-topbar {
                text-align: center;
            }
        }
    </style>

    <script>
        function initCharts(retries) {
            if (typeof Chart === 'undefined') {
                if ((retries || 0) < 20) setTimeout(function(){ initCharts((retries || 0) + 1); }, 100);
                return;
            }
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
        }
        document.addEventListener('DOMContentLoaded', initCharts);

        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardState', () => ({
                openSpiritualModal: false,
                openEmotionModal: false,
                spiritualTimeframe: '1M',
                emotionTimeframe: '1M',
                historicalData: @json($historicalData),
                spiritualChartInstance: null,
                emotionChartInstance: null,

                getFilteredData(metric, timeframe) {
                    const now = new Date();
                    let limitDate = new Date();
                    
                    if (timeframe === '1W') {
                        limitDate.setDate(now.getDate() - 7);
                    } else if (timeframe === '1M') {
                        limitDate.setMonth(now.getMonth() - 1);
                    } else if (timeframe === '3M') {
                        limitDate.setMonth(now.getMonth() - 3);
                    } else if (timeframe === '6M') {
                        limitDate.setMonth(now.getMonth() - 6);
                    } else if (timeframe === '1Y') {
                        limitDate.setFullYear(now.getFullYear() - 1);
                    } else {
                        limitDate = new Date(0); // ALL
                    }
                    
                    return this.historicalData
                        .filter(item => new Date(item.date) >= limitDate)
                        .sort((a, b) => new Date(a.date) - new Date(b.date));
                },

                getStats(metric, timeframe) {
                    const data = this.getFilteredData(metric, timeframe);
                    if (data.length === 0) return { current: '--', changeText: 'Belum ada data', changeClass: 'neutral', icon: '' };
                    
                    const current = data[data.length - 1][metric];
                    if (data.length < 2) {
                        return { current: current, changeText: 'Stabil', changeClass: 'neutral', icon: '•' };
                    }
                    
                    const first = data[0][metric];
                    const diff = current - first;
                    
                    if (diff > 0) {
                        return {
                            current: current,
                            changeText: `+${diff.toFixed(metric === 'emotion' ? 1 : 0)} poin`,
                            changeClass: 'positive',
                            icon: '↑'
                        };
                    } else if (diff < 0) {
                        return {
                            current: current,
                            changeText: `${diff.toFixed(metric === 'emotion' ? 1 : 0)} poin`,
                            changeClass: 'negative',
                            icon: '↓'
                        };
                    } else {
                        return {
                            current: current,
                            changeText: 'Stabil',
                            changeClass: 'neutral',
                            icon: '•'
                        };
                    }
                },

                updateSpiritualChart() {
                    const filtered = this.getFilteredData('spiritual', this.spiritualTimeframe);
                    const labels = filtered.map(item => item.label);
                    const data = filtered.map(item => item.spiritual);
                    
                    if (this.spiritualChartInstance) {
                        this.spiritualChartInstance.data.labels = labels;
                        this.spiritualChartInstance.data.datasets[0].data = data;
                        this.spiritualChartInstance.update();
                    } else {
                        const ctx = document.getElementById('modalSpiritualChart');
                        if (!ctx) return;
                        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(79, 155, 79, 0.3)');
                        gradient.addColorStop(1, 'rgba(79, 155, 79, 0.0)');
                        
                        this.spiritualChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Skor Spiritual',
                                    data: data,
                                    borderColor: '#4f9b4f',
                                    backgroundColor: gradient,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 5,
                                    pointHoverRadius: 7,
                                    pointBackgroundColor: '#4f9b4f',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index',
                                },
                                scales: {
                                    y: {
                                        min: 0,
                                        max: 100,
                                        ticks: {
                                            stepSize: 20,
                                            font: { family: 'Outfit', size: 11 }
                                        },
                                        grid: { color: '#e2e8f0' }
                                    },
                                    x: {
                                        ticks: { font: { family: 'Outfit', size: 11 } },
                                        grid: { display: false }
                                    }
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        bodyFont: { family: 'Outfit', size: 12 },
                                        titleFont: { family: 'Outfit', size: 12, weight: 'bold' },
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        padding: 12,
                                        cornerRadius: 8
                                    }
                                }
                            }
                        });
                    }
                },

                updateEmotionChart() {
                    const filtered = this.getFilteredData('emotion', this.emotionTimeframe);
                    const labels = filtered.map(item => item.label);
                    const data = filtered.map(item => item.emotion);
                    
                    if (this.emotionChartInstance) {
                        this.emotionChartInstance.data.labels = labels;
                        this.emotionChartInstance.data.datasets[0].data = data;
                        this.emotionChartInstance.update();
                    } else {
                        const ctx = document.getElementById('modalEmotionChart');
                        if (!ctx) return;
                        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                        gradient.addColorStop(0, 'rgba(96, 165, 250, 0.3)');
                        gradient.addColorStop(1, 'rgba(96, 165, 250, 0.0)');
                        
                        this.emotionChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Tren Emosi',
                                    data: data,
                                    borderColor: '#3b82f6',
                                    backgroundColor: gradient,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 5,
                                    pointHoverRadius: 7,
                                    pointBackgroundColor: '#3b82f6',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index',
                                },
                                scales: {
                                    y: {
                                        min: 1,
                                        max: 5,
                                        ticks: {
                                            stepSize: 1,
                                            callback: function(value) {
                                                const labels = { 1: 'Sedih', 2: 'Cemas', 3: 'Netral', 4: 'Baik', 5: 'Sgt Baik' };
                                                return labels[value] || value;
                                            },
                                            font: { family: 'Outfit', size: 10 }
                                        },
                                        grid: { color: '#e2e8f0' }
                                    },
                                    x: {
                                        ticks: { font: { family: 'Outfit', size: 11 } },
                                        grid: { display: false }
                                    }
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        bodyFont: { family: 'Outfit', size: 12 },
                                        titleFont: { family: 'Outfit', size: 12, weight: 'bold' },
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        padding: 12,
                                        cornerRadius: 8,
                                        callbacks: {
                                            label: function(context) {
                                                const labels = { 1: 'Sedih', 2: 'Cemas', 3: 'Netral', 4: 'Baik', 5: 'Sangat Baik' };
                                                return 'Kondisi: ' + (labels[context.raw] || context.raw);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            }));
        });
    </script>
</x-app-layout>
