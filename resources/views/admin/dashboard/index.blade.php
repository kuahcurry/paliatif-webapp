@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen admin-page">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="admin-main">
            <x-app-topbar
                class="admin-topbar"
                title="Dashboard Admin"
                subtitle="Panel administrasi"
                :showMenuButton="true"
            />

            <div class="admin-content">
                <div class="card">
                    <div class="section-header">
                        <div>
                            <h4>Statistik</h4>
                            <p>Gambaran umum data dalam sistem</p>
                        </div>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-icon bg-purple">D</span>
                            <div>
                                <p class="stat-label">Total Doa</p>
                                <p class="stat-value">{{ $total_prayers }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-blue">J</span>
                            <div>
                                <p class="stat-label">Total Jurnal</p>
                                <p class="stat-value">{{ $total_journals }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-green">M</span>
                            <div>
                                <p class="stat-label">Total Modul</p>
                                <p class="stat-value">{{ $total_modules }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-amber">E</span>
                            <div>
                                <p class="stat-label">Total Evaluasi</p>
                                <p class="stat-value">{{ $total_evaluations }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-slate">U</span>
                            <div>
                                <p class="stat-label">Total Pengguna</p>
                                <p class="stat-value">{{ $total_users }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-grid-2">
                    <div class="card">
                        <div class="section-header">
                            <div>
                                <h4>Pengguna Terbaru</h4>
                                <p>5 pengguna terakhir yang mendaftar</p>
                            </div>
                        </div>
                        <div class="user-list">
                            @forelse ($recent_users as $u)
                                <div class="user-row">
                                    <span class="user-avatar">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                                    <div>
                                        <p class="user-name">{{ $u->name }}</p>
                                        <p class="user-email">{{ $u->email }} · {{ $u->is_admin ? 'Admin' : 'Pasien' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="empty-state">Belum ada pengguna.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="card">
                        <div class="section-header">
                            <div>
                                <h4>Evaluasi Terbaru</h4>
                                <p>5 evaluasi perasaan terakhir</p>
                            </div>
                        </div>
                        <div class="user-list">
                            @forelse ($recent_evaluations as $e)
                                <div class="user-row">
                                    <span class="user-avatar">{{ strtoupper(substr($e->user?->name ?? '?', 0, 1)) }}</span>
                                    <div>
                                        <p class="user-name">{{ $e->user?->name ?? 'Pengguna' }}</p>
                                        <p class="user-email">{{ $e->emotion }} · {{ $e->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="empty-state">Belum ada evaluasi.</p>
                            @endforelse
                        </div>
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
            --shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .admin-page { background: #f4f6fb; font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif; }
        .admin-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .admin-sidebar { background: var(--surface); padding: 28px 20px; border-right: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 24px; }
        .sidebar-brand { display: flex; gap: 12px; align-items: center; font-weight: 700; }
        .brand-icon { width: 42px; height: 42px; border-radius: 16px; background: #dff3df; display: grid; place-items: center; }
        .brand-icon span { width: 22px; height: 22px; border-radius: 999px; background: #63b96b; display: block; }
        .sidebar-brand h1 { font-size: 0.95rem; color: var(--text); }
        .sidebar-brand p { font-size: 0.8rem; color: var(--muted); }
        .sidebar-brand small { font-size: 0.7rem; color: #94a3b8; }
        .sidebar-nav { display: flex; flex-direction: column; gap: 10px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px; color: var(--muted); text-decoration: none; font-size: 0.9rem; }
        .nav-icon { width: 20px; height: 20px; display: grid; place-items: center; color: currentColor; }
        .nav-icon svg { width: 18px; height: 18px; }
        .nav-item.is-active, .nav-item:hover { background: #e1f1e1; color: #256c32; }
        .nav-group { display: grid; gap: 6px; }
        .nav-sub { display: grid; gap: 6px; margin-left: 12px; }
        .nav-sub-item { font-size: 0.82rem; color: var(--muted); text-decoration: none; }
        .nav-sub-item.is-active { color: #256c32; font-weight: 600; }
        .sidebar-footer { margin-top: auto; }
        .footer-card { background: #f8fafc; border-radius: 16px; padding: 16px; font-size: 0.75rem; color: var(--muted); }
        .footer-title { font-weight: 700; color: var(--text); }
        .footer-subtitle { margin-bottom: 6px; }

        .admin-main { padding: 26px 32px 48px; display: flex; flex-direction: column; gap: 8px; }
        .admin-topbar { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
        .admin-topbar > div:not(.topbar-actions) { grid-column: 2; text-align: center; }
        .admin-topbar .ghost-button { grid-column: 1; justify-self: start; }
        .admin-topbar .topbar-actions { grid-column: 3; justify-self: end; }
        .admin-topbar h2 { font-size: 1.4rem; font-weight: 700; }
        .admin-topbar p { color: var(--muted); font-size: 0.85rem; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .icon-button { position: relative; border: none; background: #ffffff; box-shadow: var(--shadow); border-radius: 12px; width: 38px; height: 38px; cursor: pointer; display: grid; place-items: center; color: #475569; text-decoration: none; font-size: 1rem; font-weight: 600; }
        .icon-button svg { width: 20px; height: 20px; }
        .badge { position: absolute; top: -4px; right: -4px; background: #22c55e; color: #fff; font-size: 0.65rem; width: 18px; height: 18px; border-radius: 999px; display: grid; place-items: center; }
        .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); text-align: left; }
        .user-role { font-size: 0.75rem; color: var(--muted); text-align: left; }
        .chevron { color: var(--muted); }

        .admin-content { display: grid; gap: 8px; }
        .card { background: var(--surface); border-radius: 20px; padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 16px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .section-header h4 { font-weight: 700; }
        .section-header p { font-size: 0.78rem; color: var(--muted); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; }
        .stat-card { display: flex; align-items: center; gap: 12px; background: #f8fafc; border-radius: 16px; padding: 14px; }
        .stat-icon { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; font-weight: 700; font-size: 1rem; color: #fff; }
        .stat-icon.bg-purple { background: #8b5cf6; }
        .stat-icon.bg-blue { background: #3b82f6; }
        .stat-icon.bg-green { background: #22c55e; }
        .stat-icon.bg-amber { background: #f59e0b; }
        .stat-icon.bg-slate { background: #64748b; }
        .stat-label { font-size: 0.7rem; color: var(--muted); }
        .stat-value { font-size: 1.4rem; font-weight: 700; color: var(--text); }
        .card-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .user-list { display: grid; gap: 10px; }
        .user-row { display: flex; align-items: center; gap: 12px; background: #f8fafc; border-radius: 12px; padding: 10px 12px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 999px; background: #e2f4e2; display: grid; place-items: center; font-weight: 700; font-size: 0.8rem; color: #2f855a; }
        .user-name { font-size: 0.82rem; font-weight: 600; color: var(--text); }
        .user-email { font-size: 0.72rem; color: var(--muted); }
        .empty-state { font-size: 0.85rem; color: var(--muted); text-align: center; padding: 16px; }

        @media (max-width: 900px) {
            .admin-layout { grid-template-columns: 1fr; }
            .admin-sidebar { position: sticky; top: 0; z-index: 10; flex-direction: row; overflow-x: auto; gap: 12px; }
            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }
            .admin-topbar { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
            .admin-topbar > div:not(.topbar-actions) { text-align: left; }
            .admin-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }
            .card-grid-2 { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</x-app-layout>
