@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen notif-page">
    <div class="notif-layout">
        <aside class="notif-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="notif-main">
            <x-app-topbar
                class="notif-topbar"
                title="Notifikasi"
                subtitle="Semua pemberitahuan untuk Anda"
                :showMenuButton="true"
            />

            @if ($notifications->where('is_read', false)->count() > 0)
                <div class="card">
                    <div class="section-header">
                        <div>
                            <h4>Daftar Notifikasi</h4>
                            <p>{{ $notifications->total() }} notifikasi · {{ $notifications->where('is_read', false)->count() }} belum dibaca</p>
                        </div>
                        <form method="POST" action="{{ route('notifications.markAllRead') }}">
                            @csrf
                            <button type="submit" class="ghost-button">Tandai semua dibaca</button>
                        </form>
                    </div>
                    <div class="notif-list">
                        @forelse ($notifications as $notification)
                            <div class="notif-item {{ $notification->is_read ? '' : 'unread' }}">
                                <div class="notif-content">
                                    <div class="notif-title-row">
                                        <span class="notif-title">{{ $notification->title }}</span>
                                        @if (! $notification->is_read)
                                            <span class="notif-badge">Baru</span>
                                        @endif
                                    </div>
                                    @if ($notification->body)
                                        <p class="notif-body">{{ $notification->body }}</p>
                                    @endif
                                    <span class="notif-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="notif-actions">
                                    @if (! $notification->is_read)
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                            @csrf
                                            <button type="submit" class="ghost-button small">Tandai dibaca</button>
                                        </form>
                                    @endif
                                    @if ($notification->url)
                                        <a href="{{ $notification->url }}" class="ghost-button small">Lihat</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="empty-state">Belum ada notifikasi.</p>
                        @endforelse
                    </div>
                    <div>{{ $notifications->links() }}</div>
                </div>
            @else
                <div class="card">
                    <div class="section-header">
                        <div>
                            <h4>Notifikasi</h4>
                            <p>Tidak ada notifikasi baru</p>
                        </div>
                    </div>
                    <p class="empty-state">Belum ada notifikasi.</p>
                </div>
            @endif
        </main>
    </div>

    <style>
        :root { --surface: #fff; --surface-muted: #f7faf5; --text: #0f172a; --muted: #64748b; --shadow: 0 12px 28px rgba(15,23,42,0.08); }
        .notif-page { background: #f4f6fb; font-family: 'Manrope', sans-serif; }
        .notif-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .notif-sidebar { background: var(--surface); padding: 28px 20px; border-right: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 24px; }
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
        .notif-main { padding: 26px 32px 48px; display: flex; flex-direction: column; gap: 8px; }
        .notif-topbar { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
        .notif-topbar > div:not(.topbar-actions) { grid-column: 2; text-align: center; }
        .notif-topbar .ghost-button { grid-column: 1; justify-self: start; }
        .notif-topbar .topbar-actions { grid-column: 3; justify-self: end; }
        .notif-topbar h2 { font-size: 1.4rem; font-weight: 700; }
        .notif-topbar p { color: var(--muted); font-size: 0.85rem; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .icon-button { position: relative; border: none; background: #fff; box-shadow: var(--shadow); border-radius: 12px; width: 38px; height: 38px; cursor: pointer; display: grid; place-items: center; color: #475569; text-decoration: none; font-size: 1rem; font-weight: 600; }
        .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .user-role { font-size: 0.75rem; color: var(--muted); }
        .card { background: var(--surface); border-radius: 20px; padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 16px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .section-header h4 { font-weight: 700; }
        .section-header p { font-size: 0.78rem; color: var(--muted); }
        .ghost-button { border: 1px solid #e2e8f0; background: #fff; color: #475569; border-radius: 12px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; text-decoration: none; display: inline-flex; }
        .ghost-button.small { padding: 4px 10px; font-size: 0.72rem; }
        .empty-state { font-size: 0.85rem; color: var(--muted); text-align: center; padding: 32px; }
        .notif-list { display: grid; gap: 8px; }
        .notif-item { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: 14px; background: #f8fafc; }
        .notif-item.unread { background: #f0f7ff; border-left: 3px solid #6366f1; }
        .notif-content { flex: 1; display: grid; gap: 4px; }
        .notif-title-row { display: flex; align-items: center; gap: 8px; }
        .notif-title { font-size: 0.85rem; font-weight: 600; color: #1f2937; }
        .notif-badge { font-size: 0.65rem; padding: 2px 8px; border-radius: 999px; background: #6366f1; color: #fff; font-weight: 600; }
        .notif-body { font-size: 0.8rem; color: #64748b; }
        .notif-time { font-size: 0.7rem; color: #94a3b8; }
        .notif-actions { display: flex; gap: 6px; flex-shrink: 0; align-items: center; }
        @media (max-width: 900px) {
            .notif-layout { grid-template-columns: 1fr; }
            .notif-sidebar { position: sticky; top: 0; z-index: 10; flex-direction: row; overflow-x: auto; }
            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }
            .notif-topbar { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
            .notif-topbar > div:not(.topbar-actions) { text-align: left; }
            .notif-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }
            .notif-item { flex-direction: column; gap: 8px; }
        }
    </style>
</x-app-layout>
