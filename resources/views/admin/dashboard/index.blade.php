@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
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
                            <span class="stat-icon bg-purple">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </span>
                            <div>
                                <p class="stat-label">Total Doa</p>
                                <p class="stat-value">{{ $total_prayers }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-blue">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M4 6h16M4 10h16M4 14h12M4 18h8"/></svg>
                            </span>
                            <div>
                                <p class="stat-label">Total Jurnal</p>
                                <p class="stat-value">{{ $total_journals }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-green">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 6l8 4-8 4-8-4 8-4z M4 10l8 4 8-4 M4 14l8 4 8-4"/></svg>
                            </span>
                            <div>
                                <p class="stat-label">Total Modul</p>
                                <p class="stat-value">{{ $total_modules }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-amber">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2 M9 9h.01M15 9h.01"/></svg>
                            </span>
                            <div>
                                <p class="stat-label">Total Evaluasi</p>
                                <p class="stat-value">{{ $total_evaluations }}</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-icon bg-slate">
                                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/></svg>
                            </span>
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

    @include('admin.partials.admin-styles')

    <style>
        .stat-icon svg { width: 20px; height: 20px; }
    </style>
</x-app-layout>
