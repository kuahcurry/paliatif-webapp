@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen prayer-tree-page">
    <div class="prayer-layout">
        <aside class="prayer-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="prayer-main">
            <x-app-topbar
                class="prayer-topbar"
                title="Pohon Doa"
                subtitle="Pohon spiritual yang tumbuh bersamamu"
                :badgeCount="3"
            />

            <div class="patient-strip">
                <div class="patient-strip-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                <div class="patient-strip-info">
                    <span class="patient-strip-name">{{ $patientName }}</span>
                    <span class="patient-strip-detail">{{ $patientAge }} &middot; {{ $patientGender }}</span>
                </div>
                <div class="patient-strip-stage">
                    <span class="strip-stage-badge">Tahap {{ $treeStage }}/7</span>
                </div>
            </div>

            <div class="prayer-content">
                <div class="tree-card">
                    <div class="tree-visual">
                        <div class="tree-visual-glow"></div>
                        <div class="tree-image-wrapper">
                            <img src="{{ asset('build/assets/tree/stage' . $treeStage . '.png') }}"
                                 alt="{{ $stageName }}"
                                 class="tree-image"
                                 id="treeImage">
                            <div class="tree-stage-label">{{ $stageName }}</div>
                            <div class="tree-stages-dots">
                                @for ($i = 1; $i <= 7; $i++)
                                    <span class="stage-dot {{ $i <= $treeStage ? 'active' : '' }}"
                                          data-stage="{{ $i }}"
                                          title="Tahap {{ $i }}"></span>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="tree-info">
                        <div class="tree-quote-block">
                            <span class="quote-icon">"</span>
                            <p class="tree-quote">{{ $quote }}</p>
                        </div>

                        <div class="tree-progress">
                            <div class="progress-header">
                                <span>Pertumbuhan Pohon</span>
                                <span class="progress-count">{{ $totalActivities }} aktivitas</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $stageProgress }}%"></div>
                                </div>
                                <div class="progress-stages">
                                    @foreach ([0, 3, 9, 15, 21, 27, 33] as $threshold)
                                        <div class="progress-marker {{ $totalActivities >= $threshold ? 'reached' : '' }}"
                                             style="left: {{ ($threshold / 33) * 100 }}%">
                                            <span class="marker-dot"></span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if ($nextThreshold)
                                <p class="progress-hint">{{ $nextThreshold - $totalActivities }} aktivitas lagi ke tahap selanjutnya</p>
                            @else
                                <p class="progress-hint">Pohonmu telah mencapai puncak pertumbuhan</p>
                            @endif
                        </div>

                        <div class="tree-fertilizers">
                            <div class="fertilizer-head">
                                <h5>Pupuk Pohon</h5>
                                <span class="fertilizer-head-sub">Isi aktivitas untuk menyuburkan pohon</span>
                            </div>
                            <div class="fertilizer-list">
                                @foreach ($allFertilizers as $item)
                                    <a href="{{ route($item['route']) }}" class="fertilizer-item {{ $item['done'] ? 'done' : '' }}">
                                        <div class="fertilizer-icon">
                                            @if ($item['done'])
                                                <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 4L12 14.01l-3-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            @else
                                                <svg viewBox="0 0 24 24"><path d="M12 22s-8-4-8-10c0-4 3.5-6 8-6s8 2 8 6c0 6-8 10-8 10z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            @endif
                                        </div>
                                        <div class="fertilizer-detail">
                                            <span class="fertilizer-label">{{ $item['label'] }}</span>
                                            <span class="fertilizer-count">{{ $item['count'] }}x diisi</span>
                                        </div>
                                        <span class="fertilizer-status">{{ $item['done'] ? 'Selesai' : 'Isi sekarang' }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @if (count($recommendedModules) > 0)
                <div class="recommend-section">
                    <div class="section-header">
                        <h4>Rekomendasi untukmu</h4>
                        <p>Video dan materi yang sesuai dengan kebutuhanmu</p>
                    </div>
                    <div class="recommend-grid">
                        @foreach ($recommendedModules as $module)
                            <a href="{{ route('education.show', $module) }}" class="recommend-card">
                                @if ($module->image_path)
                                    <div class="recommend-thumb">
                                        <img src="{{ asset('storage/' . $module->image_path) }}" alt="{{ $module->title }}">
                                    </div>
                                @else
                                    <div class="recommend-thumb recommend-thumb-placeholder">
                                        <svg viewBox="0 0 24 24"><path d="M12 6l8 4-8 4-8-4 8-4z M4 10l8 4 8-4 M4 14l8 4 8-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </div>
                                @endif
                                <div class="recommend-body">
                                    <h5>{{ $module->title }}</h5>
                                    <p>{{ Str::limit($module->summary ?? $module->content, 100) }}</p>
                                    <span class="recommend-tag">{{ $module->type === 'video' ? 'Video' : 'Artikel' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

    <style>
        :root {
            --surface: #ffffff;
            --surface-muted: #f7faf5;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-light: #475569;
            --muted: #64748b;
            --accent: #4f9b4f;
            --accent-dark: #256c32;
            --accent-light: #e8f5e8;
            --accent-glow: rgba(79, 155, 79, 0.25);
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.06);
            --shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 12px 32px rgba(15, 23, 42, 0.1);
        }

        .prayer-tree-page {
            background: #f0f5f0;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
            color: var(--text);
        }

        /* ── Layout ── */

        .prayer-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .prayer-sidebar {
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

        .prayer-main {
            padding: 24px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ── Topbar ── */

        .prayer-topbar {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            gap: 16px;
        }

        .prayer-topbar > div:first-of-type {
            text-align: center;
        }

        .prayer-topbar .ghost-button {
            position: absolute;
            left: 0;
        }

        .prayer-topbar .topbar-actions {
            position: absolute;
            right: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .prayer-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .prayer-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
        }

        /* ── Topbar Profile ── */

        .prayer-topbar .icon-button {
            position: relative;
            border: none;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
            border-radius: 12px;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: grid;
            place-items: center;
            color: var(--text-light);
            transition: all 0.2s ease;
        }

        .prayer-topbar .icon-button:hover {
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
            color: var(--accent);
        }

        .prayer-topbar .icon-button svg {
            width: 20px;
            height: 20px;
        }

        .prayer-topbar .badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.6rem;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            border: 2px solid #fff;
        }

        .prayer-topbar .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border-radius: 14px;
            padding: 6px 14px 6px 6px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .prayer-topbar .user-chip:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 14px rgba(79, 155, 79, 0.15);
        }

        .prayer-topbar .avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-light), #c8e6c9);
            color: var(--accent-dark);
            font-weight: 700;
            font-size: 0.9rem;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .prayer-topbar .user-name {
            font-size: 0.85rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .prayer-topbar .user-role {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .prayer-topbar .chevron {
            color: var(--muted);
            margin-left: 2px;
            transition: transform 0.2s ease;
        }

        .prayer-topbar .user-chip:hover .chevron {
            transform: rotate(180deg);
        }

        /* ── Patient Strip ── */

        .patient-strip {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--surface);
            border-radius: 16px;
            padding: 14px 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #edf2f7;
        }

        .patient-strip-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #d4edda, #c8e6c9);
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--accent-dark);
            flex-shrink: 0;
        }

        .patient-strip-info {
            display: flex;
            flex-direction: column;
            gap: 1px;
            flex: 1;
            min-width: 0;
        }

        .patient-strip-name {
            font-weight: 700;
            font-size: 0.9rem;
        }

        .patient-strip-detail {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .patient-strip-stage {
            flex-shrink: 0;
        }

        .strip-stage-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            background: var(--accent-light);
            color: var(--accent-dark);
        }

        /* ── Tree Card ── */

        .prayer-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .tree-card {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            display: grid;
            grid-template-columns: 1fr;
            overflow: hidden;
            gap: 0;
        }

        .tree-visual {
            background: linear-gradient(160deg, #d4edda 0%, #a8dba8 40%, #85c785 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            position: relative;
            min-height: 320px;
            overflow: hidden;
        }

        .tree-visual-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 300px;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            pointer-events: none;
        }

        .tree-image-wrapper {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            z-index: 1;
        }

        .tree-image {
            max-width: 260px;
            max-height: 300px;
            object-fit: contain;
            transition: all 0.6s ease;
            filter: drop-shadow(0 12px 32px rgba(37, 108, 50, 0.35));
        }

        .tree-stage-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(37, 108, 50, 0.8);
            background: rgba(255,255,255,0.7);
            padding: 4px 14px;
            border-radius: 999px;
            backdrop-filter: blur(4px);
        }

        .tree-stages-dots {
            display: flex;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255,255,255,0.8);
            border-radius: 999px;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .stage-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #cbd5e1;
            transition: all 0.3s ease;
            cursor: default;
        }

        .stage-dot.active {
            background: var(--accent);
            box-shadow: 0 0 0 3px rgba(79, 155, 79, 0.25);
        }

        /* ── Tree Info ── */

        .tree-info {
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .tree-quote-block {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .quote-icon {
            font-size: 2.5rem;
            line-height: 1;
            color: var(--accent);
            opacity: 0.3;
            font-family: Georgia, serif;
            flex-shrink: 0;
            margin-top: -6px;
        }

        .tree-quote {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--text-light);
            font-weight: 500;
            font-style: italic;
        }

        /* ── Progress ── */

        .tree-progress {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 18px 20px;
            background: #f8faf8;
            border-radius: 16px;
            border: 1px solid #edf2f7;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text);
        }

        .progress-count {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--accent-dark);
            background: var(--accent-light);
            padding: 2px 10px;
            border-radius: 999px;
        }

        .progress-track {
            position: relative;
            height: 28px;
            display: flex;
            align-items: center;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4f9b4f, #6fbf6f);
            border-radius: 999px;
            transition: width 0.8s ease;
        }

        .progress-stages {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .progress-marker {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .marker-dot {
            display: block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #cbd5e1;
            border: 2px solid #f8faf8;
        }

        .progress-marker.reached .marker-dot {
            background: var(--accent);
        }

        .progress-hint {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: -2px;
        }

        /* ── Fertilizers ── */

        .tree-fertilizers {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .fertilizer-head {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fertilizer-head h5 {
            font-weight: 700;
            font-size: 0.9rem;
        }

        .fertilizer-head-sub {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .fertilizer-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .fertilizer-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 12px;
            align-items: center;
            padding: 12px 14px;
            border-radius: 14px;
            background: #f8faf8;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #edf2f7;
        }

        .fertilizer-item:hover {
            border-color: var(--accent);
            background: var(--accent-light);
            box-shadow: 0 2px 8px rgba(79, 155, 79, 0.1);
        }

        .fertilizer-item.done {
            background: #e8f5e8;
            border-color: #c8e6c9;
        }

        .fertilizer-item.done:hover {
            border-color: var(--accent);
        }

        .fertilizer-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #e2e8f0;
            display: grid;
            place-items: center;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .fertilizer-item.done .fertilizer-icon {
            background: var(--accent);
            color: #fff;
        }

        .fertilizer-icon svg {
            width: 18px;
            height: 18px;
        }

        .fertilizer-detail {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .fertilizer-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
        }

        .fertilizer-count {
            font-size: 0.68rem;
            color: var(--muted);
        }

        .fertilizer-status {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--accent);
            white-space: nowrap;
        }

        .fertilizer-item.done .fertilizer-status {
            color: var(--accent-dark);
        }

        /* ── Recommendations ── */

        .recommend-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .section-header h4 {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .section-header p {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .recommend-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .recommend-card {
            background: var(--surface);
            border-radius: 18px;
            box-shadow: var(--shadow);
            overflow: hidden;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid #edf2f7;
        }

        .recommend-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent);
        }

        .recommend-thumb {
            height: 140px;
            overflow: hidden;
            background: #e2e8f0;
        }

        .recommend-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .recommend-card:hover .recommend-thumb img {
            transform: scale(1.05);
        }

        .recommend-thumb-placeholder {
            display: grid;
            place-items: center;
            color: var(--muted);
        }

        .recommend-thumb-placeholder svg {
            width: 36px;
            height: 36px;
        }

        .recommend-body {
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .recommend-body h5 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
        }

        .recommend-body p {
            font-size: 0.76rem;
            color: var(--muted);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
        }

        .recommend-tag {
            font-size: 0.62rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            background: var(--accent-light);
            color: var(--accent-dark);
            align-self: flex-start;
            margin-top: 4px;
        }

        /* ── Responsive ── */

        @media (max-width: 1100px) {
            .tree-visual {
                min-height: 260px;
            }

            .tree-image {
                max-width: 180px;
                max-height: 220px;
            }
        }

        @media (max-width: 900px) {
            .prayer-layout {
                grid-template-columns: 1fr;
            }

            .prayer-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                flex-direction: row;
                overflow-x: auto;
                gap: 12px;
                padding: 12px 20px;
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

            .prayer-main {
                padding: 16px 16px 40px;
                gap: 12px;
            }

            .prayer-topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .prayer-topbar > div:first-of-type {
                text-align: left;
            }

            .prayer-topbar .topbar-actions {
                position: static;
                align-self: flex-end;
            }

            .patient-strip {
                flex-wrap: wrap;
                gap: 10px;
            }

            .tree-info {
                padding: 24px 20px;
            }

            .tree-progress {
                padding: 14px 16px;
            }
        }
    </style>
</x-app-layout>
