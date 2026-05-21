@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
@endpush

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen reader-page">
    <div class="reader-layout">
        <aside class="reader-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="reader-main">
            {{-- Sticky reading topbar --}}
            <nav class="reader-topbar">
                <a href="{{ route('education.index') }}" class="back-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg>
                    Modul Edukasi
                </a>
                <div class="topbar-right">
                    <span class="type-pill {{ $module->type }}">{{ $module->type === 'video' ? '▶ Video' : '📄 Artikel' }}</span>
                    <span class="read-time">{{ ceil(str_word_count($module->content ?? '') / 200) ?: 3 }} menit baca</span>
                </div>
            </nav>

            {{-- Article container (Medium-style centered column) --}}
            <article class="article-container">

                {{-- Hero section --}}
                <header class="article-header">
                    @if ($module->tags)
                        <div class="article-tags-top">
                            @foreach ($module->tags as $tag)
                                @php
                                    $tagLabels = ['coping' => 'Coping & Tenang', 'communication' => 'Komunikasi', 'grief' => 'Manajemen Duka', 'spiritual_support' => 'Dukungan Spiritual'];
                                @endphp
                                <a href="{{ route('education.index', ['tag' => $tag]) }}" class="tag-link">{{ $tagLabels[$tag] ?? $tag }}</a>
                            @endforeach
                        </div>
                    @endif
                    <h1>{{ $module->title }}</h1>
                    @if ($module->summary)
                        <p class="article-subtitle">{{ $module->summary }}</p>
                    @endif
                    <div class="article-meta">
                        <div class="meta-author">
                            <span class="author-avatar">RH</span>
                            <div>
                                <span class="author-name">Ruang Hening</span>
                                <span class="meta-date">{{ $module->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                {{-- Featured image --}}
                @if ($module->type === 'article' && $module->image_path)
                    <figure class="article-hero-image">
                        <img src="{{ Storage::url($module->image_path) }}" alt="{{ $module->title }}">
                    </figure>
                @endif

                {{-- Video embed --}}
                @php
                    $isYoutube = $module->type === 'video' && $module->url && preg_match('/(youtube\.com|youtu\.be)/', $module->url);
                    $embedUrl = '';
                    if ($isYoutube) {
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $module->url, $matches);
                        if (isset($matches[1])) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                        }
                    }
                @endphp
                @if ($module->type === 'video' && ($module->url || $module->video_path))
                    <div class="video-section">
                        @if ($embedUrl)
                            <div class="video-embed">
                                <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @elseif ($module->video_path)
                            <video controls class="video-native">
                                <source src="{{ Storage::url($module->video_path) }}" type="video/mp4">
                            </video>
                        @else
                            <div class="video-link-box">
                                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                <p>Tonton video: <a href="{{ $module->url }}" target="_blank" rel="noopener">{{ $module->url }}</a></p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Article body --}}
                @if ($module->content)
                    <div class="article-body">
                        {!! nl2br(e($module->content)) !!}
                    </div>
                @endif

                {{-- Bottom tags --}}
                @if ($module->tags)
                    <footer class="article-footer">
                        <div class="footer-tags">
                            @foreach ($module->tags as $tag)
                                @php $tagLabels = ['coping' => 'Coping & Tenang', 'communication' => 'Komunikasi', 'grief' => 'Manajemen Duka', 'spiritual_support' => 'Dukungan Spiritual']; @endphp
                                <span class="footer-tag">{{ $tagLabels[$tag] ?? $tag }}</span>
                            @endforeach
                        </div>
                    </footer>
                @endif
            </article>

            {{-- Related modules --}}
            @if ($otherModules->isNotEmpty())
                <section class="related-section">
                    <div class="related-header">
                        <h3>Baca juga</h3>
                        <a href="{{ route('education.index') }}" class="see-all">Lihat semua →</a>
                    </div>
                    <div class="related-grid">
                        @foreach ($otherModules as $other)
                            <a class="related-card" href="{{ route('education.show', $other) }}">
                                <span class="related-type {{ $other->type }}">{{ $other->type === 'video' ? '▶' : '📄' }}</span>
                                <div>
                                    <h4>{{ $other->title }}</h4>
                                    <p>{{ Str::limit($other->summary, 90) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>
    </div>

    <style>
        :root {
            --surface: #ffffff;
            --bg: #fafafa;
            --text: #1a1a1a;
            --text-secondary: #6b7280;
            --accent: #2e7d32;
            --accent-bg: #e8f5e9;
            --border: #f0f0f0;
            --shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .reader-page {
            background: var(--bg);
            font-family: 'Outfit', ui-sans-serif, system-ui, sans-serif;
            color: var(--text);
        }

        .reader-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .reader-sidebar {
            background: var(--surface);
            padding: 28px 20px;
            border-right: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── Sidebar shared styles ── */
        .sidebar-brand { display: flex; gap: 12px; align-items: center; font-weight: 700; }
        .brand-icon { width: 42px; height: 42px; border-radius: 16px; background: #dff3df; display: grid; place-items: center; }
        .brand-icon span { width: 22px; height: 22px; border-radius: 999px; background: #63b96b; display: block; }
        .sidebar-brand h1 { font-size: 0.95rem; }
        .sidebar-brand p { font-size: 0.8rem; color: var(--text-secondary); }
        .sidebar-nav { display: flex; flex-direction: column; gap: 10px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px; color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; }
        .nav-icon { width: 20px; height: 20px; display: grid; place-items: center; }
        .nav-icon svg { width: 18px; height: 18px; }
        .nav-item.is-active, .nav-item:hover { background: #e1f1e1; color: #256c32; }
        .sidebar-footer { margin-top: auto; }
        .footer-card { background: #f8fafc; border-radius: 16px; padding: 16px; font-size: 0.75rem; color: var(--text-secondary); }
        .footer-title { font-weight: 700; color: var(--text); }
        .sidebar-divider { height: 1px; background: #edf2f7; margin: 12px 0 8px; }
        .sidebar-section-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; padding: 0 12px; font-weight: 700; }

        /* ── Topbar shared ── */
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .icon-button { position: relative; border: none; background: #fff; box-shadow: var(--shadow); border-radius: 12px; width: 38px; height: 38px; cursor: pointer; display: grid; place-items: center; color: #475569; text-decoration: none; font-size: 1rem; font-weight: 600; }
        .icon-button svg { width: 20px; height: 20px; }
        .badge { position: absolute; top: -4px; right: -4px; background: #22c55e; color: #fff; font-size: 0.65rem; width: 18px; height: 18px; border-radius: 999px; display: grid; place-items: center; }
        .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
        .user-name { font-size: 0.85rem; font-weight: 600; }
        .user-role { font-size: 0.75rem; color: var(--text-secondary); }
        .chevron { color: var(--text-secondary); }

        /* ── Reader main ── */
        .reader-main {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Reading topbar ── */
        .reader-topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 32px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.15s;
        }
        .back-link svg { width: 16px; height: 16px; }
        .back-link:hover { color: var(--accent); }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .type-pill {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
        }
        .type-pill.video { background: #f3e8ff; color: #7c3aed; }
        .type-pill.article { background: #dcfce7; color: #15803d; }
        .read-time { font-size: 0.75rem; color: #94a3b8; }

        /* ── Article container (Medium-style) ── */
        .article-container {
            max-width: 720px;
            margin: 0 auto;
            padding: 48px 24px 64px;
            width: 100%;
        }

        /* ── Article header ── */
        .article-header {
            margin-bottom: 32px;
        }

        .article-tags-top {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .tag-link {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent);
            text-decoration: none;
            padding: 4px 12px;
            border-radius: 999px;
            background: var(--accent-bg);
            transition: all 0.15s;
        }
        .tag-link:hover { background: #c8e6c9; }

        .article-header h1 {
            font-family: 'Merriweather', 'Georgia', serif;
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.02em;
            color: var(--text);
            margin: 0 0 16px;
        }

        .article-subtitle {
            font-size: 1.15rem;
            line-height: 1.55;
            color: var(--text-secondary);
            margin: 0 0 24px;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .meta-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: linear-gradient(135deg, #4caf50, #2e7d32);
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .author-name {
            display: block;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--text);
        }

        .meta-date {
            display: block;
            font-size: 0.78rem;
            color: #94a3b8;
        }

        /* ── Hero image ── */
        .article-hero-image {
            margin: 0 -24px 36px;
            border-radius: 12px;
            overflow: hidden;
        }

        .article-hero-image img {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            background: #f1f5f9;
            display: block;
        }

        /* ── Video section ── */
        .video-section {
            margin-bottom: 36px;
        }

        .video-embed {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 12px;
            background: #000;
        }

        .video-embed iframe {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            border: none;
        }

        .video-native {
            width: 100%;
            max-height: 480px;
            border-radius: 12px;
            background: #000;
        }

        .video-link-box {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 24px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid var(--border);
            color: var(--text-secondary);
        }
        .video-link-box a { color: var(--accent); word-break: break-all; }

        /* ── Article body (Medium typography) ── */
        .article-body {
            font-family: 'Merriweather', 'Georgia', serif;
            font-size: 1.05rem;
            line-height: 1.85;
            color: #292929;
            letter-spacing: -0.003em;
            word-break: break-word;
        }

        .article-body br + br {
            content: '';
            display: block;
            margin-top: 0.5em;
        }

        /* ── Article footer ── */
        .article-footer {
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .footer-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .footer-tag {
            font-size: 0.78rem;
            padding: 6px 14px;
            border-radius: 999px;
            background: #f1f5f9;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* ── Related modules ── */
        .related-section {
            max-width: 720px;
            margin: 0 auto;
            padding: 0 24px 64px;
            width: 100%;
        }

        .related-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .related-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .see-all {
            font-size: 0.82rem;
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
        }

        .related-grid {
            display: grid;
            gap: 12px;
        }

        .related-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
            background: var(--surface);
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }

        .related-card:hover {
            border-color: #c8e6c9;
            box-shadow: 0 4px 16px rgba(46,125,50,0.06);
            transform: translateY(-1px);
        }

        .related-type {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .related-type.video { background: #f3e8ff; }
        .related-type.article { background: #dcfce7; }

        .related-card h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0 0 4px;
            line-height: 1.3;
        }

        .related-card p {
            font-size: 0.78rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.4;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .reader-layout { grid-template-columns: 1fr; }
            .reader-sidebar { position: sticky; top: 0; z-index: 30; flex-direction: row; overflow-x: auto; gap: 12px; padding: 12px 16px; }
            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }
            .reader-topbar { padding: 10px 16px; }
            .article-container { padding: 28px 16px 48px; }
            .article-header h1 { font-size: 1.6rem; }
            .article-subtitle { font-size: 1rem; }
            .article-body { font-size: 0.95rem; }
            .article-hero-image { margin: 0 -16px 24px; }
            .related-section { padding: 0 16px 48px; }
        }
    </style>
</x-app-layout>
