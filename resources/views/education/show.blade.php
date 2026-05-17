@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen education-page">
    <div class="education-layout">
        <aside class="education-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="education-main">
            <x-app-topbar
                class="education-topbar"
                title="{{ $module->title }}"
                subtitle="Modul Edukasi"
            />

            <nav class="breadcrumb">
                <a href="{{ route('education.index') }}">Modul Edukasi</a>
                <span>/</span>
                <span>{{ $module->title }}</span>
            </nav>

            <div class="module-detail">
                <div class="card detail-card">
                    <div class="detail-header">
                        <span class="type-badge {{ $module->type }}">{{ $module->type === 'video' ? 'Video' : 'Artikel' }}</span>
                        <h2>{{ $module->title }}</h2>
                        <p class="detail-summary">{{ $module->summary }}</p>
                    </div>

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
                        <div class="video-wrapper">
                            @if ($embedUrl)
                                <div class="video-embed">
                                    <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen></iframe>
                                </div>
                            @elseif ($module->video_path)
                                <video controls class="video-player" style="width:100%;max-height:500px;border-radius:12px;">
                                    <source src="{{ Storage::url($module->video_path) }}" type="video/mp4">
                                </video>
                            @else
                                <div class="video-placeholder">
                                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    <p>Tautan video: <a href="{{ $module->url }}" target="_blank">{{ $module->url }}</a></p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($module->type === 'article' && ($module->content || $module->image_path))
                        @if ($module->image_path)
                            <div class="article-image">
                                <img src="{{ Storage::url($module->image_path) }}" alt="{{ $module->title }}">
                            </div>
                        @endif
                        @if ($module->content)
                            <div class="article-content">
                                {!! nl2br(e($module->content)) !!}
                            </div>
                        @endif
                    @endif

                    @if ($module->tags)
                        <div class="detail-tags">
                            @foreach ($module->tags as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="card">
                    <div class="section-header">
                        <div>
                            <h4>Modul Lainnya</h4>
                            <p>Jelajahi modul edukasi lainnya</p>
                        </div>
                        <a href="{{ route('education.index') }}" class="ghost-button">Lihat Semua</a>
                    </div>
                    @if ($otherModules->isNotEmpty())
                        <div class="other-modules">
                            @foreach ($otherModules as $other)
                                <a class="other-module-card" href="{{ route('education.show', $other) }}">
                                    <span class="type-badge mini {{ $other->type }}">{{ $other->type === 'video' ? 'Video' : 'Artikel' }}</span>
                                    <h5>{{ $other->title }}</h5>
                                    <p>{{ Str::limit($other->summary, 80) }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="empty-state">Belum ada modul lain.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <style>
        :root { --surface: #fff; --surface-muted: #f7faf5; --text: #0f172a; --muted: #64748b; --shadow: 0 12px 28px rgba(15,23,42,0.08); }
        .education-page { background: #f4f6fb; font-family: 'Manrope', sans-serif; }
        .education-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .education-sidebar { background: var(--surface); padding: 28px 20px; border-right: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 24px; }
        .sidebar-brand { display: flex; gap: 12px; align-items: center; font-weight: 700; }
        .brand-icon { width: 42px; height: 42px; border-radius: 16px; background: #dff3df; display: grid; place-items: center; }
        .brand-icon span { width: 22px; height: 22px; border-radius: 999px; background: #63b96b; display: block; }
        .sidebar-brand h1 { font-size: 0.95rem; color: var(--text); }
        .sidebar-brand p { font-size: 0.8rem; color: var(--muted); }
        .sidebar-nav { display: flex; flex-direction: column; gap: 10px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px; color: var(--muted); text-decoration: none; font-size: 0.9rem; }
        .nav-icon { width: 20px; height: 20px; display: grid; place-items: center; }
        .nav-icon svg { width: 18px; height: 18px; }
        .nav-item.is-active, .nav-item:hover { background: #e1f1e1; color: #256c32; }
        .sidebar-footer { margin-top: auto; }
        .footer-card { background: #f8fafc; border-radius: 16px; padding: 16px; font-size: 0.75rem; color: var(--muted); }
        .footer-title { font-weight: 700; color: var(--text); }
        .education-main { padding: 26px 32px 48px; display: flex; flex-direction: column; gap: 8px; }
        .education-topbar { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
        .education-topbar > div:not(.topbar-actions) { grid-column: 2; text-align: center; }
        .education-topbar .ghost-button { grid-column: 1; justify-self: start; }
        .education-topbar .topbar-actions { grid-column: 3; justify-self: end; }
        .education-topbar h2 { font-size: 1.4rem; font-weight: 700; }
        .education-topbar p { color: var(--muted); font-size: 0.85rem; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .icon-button { position: relative; border: none; background: #fff; box-shadow: var(--shadow); border-radius: 12px; width: 38px; height: 38px; cursor: pointer; display: grid; place-items: center; color: #475569; text-decoration: none; font-size: 1rem; font-weight: 600; }
        .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .user-role { font-size: 0.75rem; color: var(--muted); }
        .card { background: var(--surface); border-radius: 20px; padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 16px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .section-header h4 { font-weight: 700; }
        .section-header p { font-size: 0.78rem; color: var(--muted); }
        .ghost-button { border: 1px solid #e2e8f0; background: #fff; color: #475569; border-radius: 12px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; text-decoration: none; }

        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: var(--muted); }
        .breadcrumb a { color: #2f855a; text-decoration: none; }
        .module-detail { display: grid; gap: 8px; }
        .detail-card { gap: 20px; }
        .detail-header { display: grid; gap: 12px; }
        .detail-header h2 { font-size: 1.4rem; font-weight: 700; }
        .detail-summary { font-size: 0.9rem; color: var(--muted); line-height: 1.5; }
        .type-badge { display: inline-flex; width: fit-content; padding: 4px 12px; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .type-badge.video { background: #f3e8ff; color: #7c3aed; }
        .type-badge.article { background: #dcfce7; color: #15803d; }
        .video-wrapper { background: #f8fafc; border-radius: 16px; overflow: hidden; }
        .video-wrapper:not(:has(.video-embed)) { padding: 40px 20px; text-align: center; }
        .video-placeholder { display: grid; gap: 12px; justify-items: center; color: var(--muted); }
        .video-embed { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; }
        .video-embed iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
        .video-placeholder a { color: #2f855a; }
        .article-content { font-size: 0.92rem; line-height: 1.8; color: var(--text); white-space: pre-wrap; }
        .article-image { border-radius: 16px; overflow: hidden; }
        .article-image img { width: 100%; max-height: 400px; object-fit: cover; border-radius: 16px; }
        .video-player { background: #000; border-radius: 12px; }
        .detail-tags { display: flex; flex-wrap: wrap; gap: 6px; }
        .tag { background: #f1f5f9; padding: 4px 10px; border-radius: 999px; font-size: 0.75rem; color: #475569; }
        .empty-state { font-size: 0.85rem; color: var(--muted); text-align: center; padding: 16px; }
        .other-modules { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; }
        .other-module-card { border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px; text-decoration: none; color: inherit; display: grid; gap: 6px; transition: box-shadow 0.2s; }
        .other-module-card:hover { box-shadow: 0 4px 12px rgba(15,23,42,0.08); }
        .other-module-card h5 { font-size: 0.82rem; font-weight: 600; }
        .other-module-card p { font-size: 0.72rem; color: var(--muted); }
        .type-badge.mini { font-size: 0.65rem; padding: 2px 8px; width: fit-content; }

        @media (max-width: 900px) {
            .education-layout { grid-template-columns: 1fr; }
            .education-sidebar { position: sticky; top: 0; z-index: 10; flex-direction: row; overflow-x: auto; gap: 12px; }
            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }
            .education-topbar { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
            .education-topbar > div:not(.topbar-actions) { text-align: left; }
            .education-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }
        }
    </style>
</x-app-layout>
