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
    $assessmentSummary = $assessmentSummary ?? [];
    $tagIcons = [
        'coping' => '<path d="M12 3c-4 0-7 3-7 7 0 4 3 11 7 11s7-7 7-11c0-4-3-7-7-7zM9 9h6M9 13h3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'communication' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        'grief' => '<path d="M19 14c1.5-2.5 2-5 2-5.5a4.5 4.5 0 0 0-9 0c0 .5.5 3 2 5.5M12 3C8 6 6 10 6 14a6 6 0 0 0 12 0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
        'spiritual_support' => '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
    ];
    $firstTag = fn($tags) => $tags ? $tagIcons[$tags[0]] ?? null : null;
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
            />
            @if (! $hasAssessment)
                <div class="notice warning">
                    Rekomendasi akan lebih tepat jika pengkajian sudah diisi.
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
                                            @if ($icon = $firstTag($module->tags))
                                                <svg class="thumb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">{!! $icon !!}</svg>
                                            @endif
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
                                            @if ($icon = $firstTag($module->tags))
                                                <svg class="thumb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">{!! $icon !!}</svg>
                                            @endif
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
                    <div class="card category-card">
                        <div class="section-header">
                            <div>
                                <h4>Kategori Modul</h4>
                                <p>Pilih kategori sesuai kebutuhan Anda.</p>
                            </div>
                        </div>
                        <div class="category-list">
                            @foreach ($categoryItems as $item)
                                @php
                                    $catColors = match ($item['tag']) {
                                        'coping' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'M12 3c-4 0-7 3-7 7 0 4 3 11 7 11s7-7 7-11c0-4-3-7-7-7zM9 9h6M9 13h3'],
                                        'communication' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => 'M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z'],
                                        'grief' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'M19 14c1.5-2.5 2-5 2-5.5a4.5 4.5 0 0 0-9 0c0 .5.5 3 2 5.5M12 3C8 6 6 10 6 14a6 6 0 0 0 12 0'],
                                        'spiritual_support' => ['bg' => '#dcfce7', 'text' => '#166534', 'icon' => 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'],
                                        default => ['bg' => '#f1f5f9', 'text' => '#475569', 'icon' => 'M12 6l8 4-8 4-8-4 8-4z'],
                                    };
                                    $isActive = $tag === $item['tag'];
                                @endphp
                                <a href="{{ $isActive ? route('education.index') : route('education.index', ['tag' => $item['tag']]) }}" class="category-item {{ $isActive ? 'active' : '' }}">
                                    <div class="category-item-left">
                                        <span class="category-icon" style="background: {{ $catColors['bg'] }}; color: {{ $catColors['text'] }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $catColors['icon'] }}"/></svg>
                                        </span>
                                        <span class="category-label">{{ $item['label'] }}</span>
                                    </div>
                                    <span class="category-count" style="background: {{ $catColors['bg'] }}; color: {{ $catColors['text'] }}">{{ $item['count'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="card help-card">
                        <div class="help-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <div class="help-content">
                            <h4>Butuh Bantuan?</h4>
                            <p>Jika Anda merasa perlu dukungan lebih lanjut dari tenaga profesional, kami siap membantu.</p>
                            <a href="{{ route('contact') }}" class="help-button">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                Hubungi Kami
                            </a>
                        </div>
                    </div>

                    <div class="card tips-card">
                        <div class="tips-glow"></div>
                        <div class="tips-content">
                            <div class="tips-icon-wrap">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8-4-8-10c0-4 3.5-6 8-6s8 2 8 6c0 6-8 10-8 10z"/><path d="M12 14a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
                            </div>
                            <h4>Tips Hari Ini</h4>
                            @if ($dailyTip)
                                <p>{{ $dailyTip }}</p>
                            @else
                                <p>Luangkan waktu sejenak untuk diri sendiri setiap hari, meski hanya 10 menit. Anda juga berhak untuk merasa lelah.</p>
                            @endif
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
            font-family: 'Outfit', ui-sans-serif, system-ui, -apple-system, sans-serif;
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

        .education-topbar .topbar-title-wrapper {
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .thumb-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 36px;
            height: 36px;
            color: rgba(0,0,0,0.15);
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
            grid-template-columns: 2fr repeat(2, minmax(0, 1fr)) auto;
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

        .category-card .section-header {
            margin-bottom: 4px;
        }

        .category-list {
            display: grid;
            gap: 6px;
        }

        .category-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 14px;
            background: #f8fafc;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .category-item:hover {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .category-item.active {
            background: #f0fdf4;
            border-color: #86efac;
        }

        .category-item-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .category-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .category-icon svg {
            width: 17px;
            height: 17px;
        }

        .category-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1f2937;
        }

        .category-item:hover .category-label {
            color: #166534;
        }

        .category-count {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            min-width: 28px;
            text-align: center;
        }

        .help-card {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            background: linear-gradient(135deg, #fef9c3 0%, #fef3c7 100%);
            border: 1px solid #fde68a;
        }

        .help-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: #fff;
            display: grid;
            place-items: center;
            color: #d97706;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(217,119,6,0.12);
        }

        .help-icon svg {
            width: 22px;
            height: 22px;
        }

        .help-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .help-content h4 {
            font-weight: 700;
            color: #92400e;
        }

        .help-content p {
            font-size: 0.78rem;
            color: #a16207;
            line-height: 1.5;
        }

        .help-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            background: #fff;
            color: #92400e;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #fde68a;
            align-self: flex-start;
            transition: all 0.2s ease;
        }

        .help-button:hover {
            background: #92400e;
            color: #fff;
            border-color: #92400e;
        }

        .help-button svg {
            width: 16px;
            height: 16px;
        }

        .tips-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
        }

        .tips-glow {
            position: absolute;
            top: -40px;
            right: -40px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34,197,94,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .tips-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .tips-icon-wrap {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #fff;
            display: grid;
            place-items: center;
            color: #16a34a;
            box-shadow: 0 2px 8px rgba(22,163,74,0.12);
        }

        .tips-icon-wrap svg {
            width: 20px;
            height: 20px;
        }

        .tips-content h4 {
            font-weight: 700;
            color: #166534;
        }

        .tips-content p {
            font-size: 0.78rem;
            color: #15803d;
            line-height: 1.5;
        }

        .tips-link {
            font-size: 0.75rem;
            font-weight: 600;
            color: #16a34a;
            text-decoration: none;
        }

        .tips-link:hover {
            text-decoration: underline;
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

            .filter-bar {
                grid-template-columns: 1fr;
            }

            .module-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            .module-grid.compact {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 600px) {
            .module-grid,
            .module-grid.compact {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</x-app-layout>
