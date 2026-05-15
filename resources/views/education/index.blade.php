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
    $caregiverName = $_user->name . ' (Caregiver)';
    $nurseName = $_user->name;
    $nurseRole = $_user->is_admin ? 'Admin' : 'Pasien';
    $assessmentSummary = $assessmentSummary ?? [];
    $categoryItems = $categoryItems ?? [];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen education-page">
    <div class="education-layout">
        <aside class="education-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="education-main">
            <x-app-topbar
                class="education-topbar"
                title="Modul Edukasi untuk Caregiver"
                subtitle="Edukasi spiritual-psikososial"
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
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Caregiver Utama</p>
                            <p class="metric-value">{{ $caregiverName }}</p>
                            <a class="metric-link" href="{{ route('profile.edit') }}">Lihat Profil Caregiver</a>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12h-4l-3 9L9 3l-3 9H2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Ringkasan Pengkajian</p>
                            <div class="metric-tags">
                                @foreach ($assessmentSummary as $summary)
                                    <span class="status-pill {{ $summary['tone'] }}">{{ $summary['label'] }}: {{ $summary['value'] }}</span>
                                @endforeach
                            </div>
                            <a class="metric-link" href="{{ route('menu.assessment') }}">Lihat Hasil Pengkajian</a>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Rekomendasi Modul untuk Anda</p>
                            <p class="metric-value">Konten sesuai hasil pengkajian</p>
                            <a class="metric-link" href="#recommended">Lihat Rekomendasi</a>
                        </div>
                    </div>
                </div>
            </section>

            @if (! $hasAssessment)
                <div class="notice warning">
                    Rekomendasi akan lebih tepat jika pengkajian caregiver sudah diisi.
                    <a href="{{ route('menu.assessment') }}">Isi pengkajian sekarang</a>
                </div>
            @endif

            <section class="education-body">
                <div class="education-left">
                    <div id="recommended" class="card">
                        <div class="section-header">
                            <div>
                                <h4>Rekomendasi untuk Anda</h4>
                                <p>Modul yang disarankan sesuai kondisi dan kebutuhan Anda saat ini.</p>
                            </div>
                            <a class="link" href="{{ route('education.index') }}">Lihat Semua</a>
                        </div>

                        @if ($recommendedModules->isEmpty())
                            <p class="empty-state">Belum ada rekomendasi modul.</p>
                        @else
                            <div class="module-grid">
                                @foreach ($recommendedModules as $module)
                                    @php
                                        $typeLabel = strtolower($module->type) === 'video' ? 'Video' : 'Artikel';
                                        $thumbClass = $typeLabel === 'Video' ? 'video' : 'article';
                                        $duration = 4 + ($loop->index % 4);
                                        $durationLabel = $typeLabel === 'Video' ? $duration . ' menit video' : $duration . ' menit baca';
                                    @endphp
                                    <a class="module-card" href="{{ route('education.show', $module) }}">
                                        <div class="module-thumb {{ $thumbClass }}">
                                            <span class="type-pill {{ $thumbClass }}">{{ $typeLabel }}</span>
                                        </div>
                                        <h5>{{ $module->title }}</h5>
                                        <p>{{ \Illuminate\Support\Str::limit($module->summary ?? $module->content, 120) }}</p>
                                        <div class="module-meta">{{ $durationLabel }}</div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="card">
                        <div class="section-header">
                            <div>
                                <h4>Semua Modul</h4>
                                <p>Jelajahi berbagai modul edukasi untuk menambah pengetahuan dan keterampilan.</p>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('education.index') }}" class="filter-bar">
                            <input type="text" name="q" placeholder="Cari modul, topik, atau kata kunci...">
                            <select id="tag_filter" name="tag">
                                <option value="all" @selected($tag === 'all')>Semua Jenis</option>
                                <option value="coping" @selected($tag === 'coping')>Coping dan tenang</option>
                                <option value="communication" @selected($tag === 'communication')>Komunikasi</option>
                                <option value="grief" @selected($tag === 'grief')>Manajemen duka</option>
                                <option value="spiritual_support" @selected($tag === 'spiritual_support')>Dukungan spiritual</option>
                            </select>
                            <select name="duration" aria-label="Durasi">
                                <option value="">Semua Durasi</option>
                            </select>
                            <select name="sort" aria-label="Urutan">
                                <option value="latest">Terbaru</option>
                            </select>
                            <button type="submit" class="ghost-button">Terapkan</button>
                        </form>

                        @if ($modules->isEmpty())
                            <p class="empty-state">Belum ada konten edukasi.</p>
                        @else
                            <div class="module-grid compact">
                                @foreach ($modules as $module)
                                    @php
                                        $typeLabel = strtolower($module->type) === 'video' ? 'Video' : 'Artikel';
                                        $thumbClass = $typeLabel === 'Video' ? 'video' : 'article';
                                        $duration = 3 + ($loop->index % 5);
                                        $durationLabel = $typeLabel === 'Video' ? $duration . ' menit video' : $duration . ' menit baca';
                                    @endphp
                                    <a class="module-card" href="{{ route('education.show', $module) }}">
                                        <div class="module-thumb {{ $thumbClass }}">
                                            <span class="type-pill {{ $thumbClass }}">{{ $typeLabel }}</span>
                                        </div>
                                        <h5>{{ $module->title }}</h5>
                                        <p>{{ \Illuminate\Support\Str::limit($module->summary ?? $module->content, 110) }}</p>
                                        <div class="module-meta">{{ $durationLabel }}</div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <aside class="education-right">
                    <div class="card">
                        <div class="section-header">
                            <div>
                                <h4>Kategori Modul</h4>
                                <p>Pilih kategori sesuai kebutuhan Anda.</p>
                            </div>
                        </div>
                        <div class="category-list">
                            @foreach ($categoryItems as $item)
                                <div class="category-item">
                                    <div>
                                        <span class="category-dot"></span>
                                        {{ $item['label'] }}
                                    </div>
                                    <span class="category-count">{{ $item['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('education.index') }}" class="ghost-button">Lihat Semua Kategori</a>
                    </div>

                    <div class="card help-card">
                        <div>
                            <h4>Butuh Bantuan?</h4>
                            <p>Jika Anda merasa perlu dukungan lebih lanjut dari tenaga profesional, kami siap membantu.</p>
                            <a href="{{ route('faq') }}" class="ghost-button">Hubungi Kami</a>
                        </div>
                        <div class="helper-illustration" aria-hidden="true">
                            <span class="helper-head"></span>
                            <span class="helper-body"></span>
                        </div>
                    </div>

                    <div class="card tips-card">
                        <h4>Tips Hari Ini</h4>
                        <p>Luangkan waktu sejenak untuk diri sendiri setiap hari, meski hanya 10 menit. Anda juga berhak untuk merasa lelah.</p>
                        <div class="tips-icon" aria-hidden="true">
                            <span></span>
                        </div>
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

        .education-page {
            background: #f4f6fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .education-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .education-sidebar {
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

        .education-main {
            padding: 26px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .education-left,
        .education-right {
            display: grid;
            gap: 8px;
        }

        .education-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .education-topbar > div:not(.topbar-actions) {
            grid-column: 2;
            text-align: center;
        }

        .education-topbar .ghost-button {
            grid-column: 1;
            justify-self: start;
        }

        .education-topbar .topbar-actions {
            grid-column: 3;
            justify-self: end;
        }

        .education-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .education-topbar p {
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

        .metric-link {
            font-size: 0.7rem;
            color: #2f855a;
        }

        .metric-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
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

        .status-pill.neutral {
            background: #e2e8f0;
            color: #475569;
        }

        .status-pill.warn {
            background: #fee2e2;
            color: #b91c1c;
        }

        .notice {
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 0.85rem;
            background: #fef3c7;
            color: #92400e;
        }

        .notice a {
            font-weight: 600;
            margin-left: 6px;
            color: inherit;
        }

        .education-body {
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

        .link {
            font-size: 0.75rem;
            color: #2f855a;
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .module-grid.compact {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .module-card {
            border-radius: 16px;
            background: #f8fafc;
            padding: 12px;
            display: grid;
            gap: 8px;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            transition: box-shadow 0.2s ease;
        }

        .module-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        }

        .module-thumb {
            position: relative;
            height: 110px;
            border-radius: 14px;
            background: linear-gradient(120deg, #dbeafe, #fef3c7);
            overflow: hidden;
        }

        .module-thumb.video {
            background: linear-gradient(120deg, #fde68a, #fca5a5);
        }

        .module-thumb.article {
            background: linear-gradient(120deg, #bbf7d0, #dbeafe);
        }

        .type-pill {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.65rem;
            padding: 4px 8px;
            border-radius: 999px;
            background: #ffffff;
            color: #1f2937;
            font-weight: 600;
        }

        .type-pill.video {
            color: #9333ea;
        }

        .type-pill.article {
            color: #16a34a;
        }

        .module-card h5 {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1f2937;
        }

        .module-card p {
            font-size: 0.72rem;
            color: var(--muted);
        }

        .module-meta {
            font-size: 0.7rem;
            color: #64748b;
        }

        .filter-bar {
            display: grid;
            grid-template-columns: 2fr repeat(3, minmax(0, 1fr)) auto;
            gap: 8px;
            align-items: center;
        }

        .filter-bar input,
        .filter-bar select {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 10px;
            font-size: 0.78rem;
        }

        .empty-state {
            font-size: 0.85rem;
            color: var(--muted);
        }

        .category-list {
            display: grid;
            gap: 10px;
        }

        .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #1f2937;
        }

        .category-item div {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .category-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #22c55e;
        }

        .category-count {
            font-size: 0.75rem;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 999px;
            color: #475569;
        }

        .help-card {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            align-items: center;
        }

        .helper-illustration {
            width: 70px;
            height: 70px;
            position: relative;
        }

        .helper-head {
            position: absolute;
            top: 8px;
            left: 20px;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: #fde68a;
        }

        .helper-body {
            position: absolute;
            bottom: 6px;
            left: 12px;
            width: 46px;
            height: 32px;
            border-radius: 16px 16px 12px 12px;
            background: #a5b4fc;
        }

        .tips-card {
            position: relative;
            overflow: hidden;
        }

        .tips-card p {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .tips-icon {
            position: absolute;
            right: 16px;
            bottom: 12px;
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: #dcfce7;
            display: grid;
            place-items: center;
        }

        .tips-icon span {
            width: 22px;
            height: 22px;
            border-radius: 999px 999px 0 999px;
            background: #22c55e;
            transform: rotate(-20deg);
            display: block;
        }

        @media (max-width: 1300px) {
            .module-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .module-grid.compact {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 1100px) {
            .patient-card {
                grid-template-columns: 1fr;
            }

            .patient-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .education-body {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .education-layout {
                grid-template-columns: 1fr;
            }

            .education-sidebar {
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

            .filter-bar {
                grid-template-columns: 1fr;
            }

            .education-topbar {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .education-topbar > div:not(.topbar-actions) {
                text-align: left;
            }

            .education-topbar .topbar-actions {
                justify-self: auto;
                align-self: flex-start;
            }
        }
    </style>
</x-app-layout>
