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
                title="Moderasi Jurnal"
                subtitle="Kelola catatan reflektif pengguna"
                :showMenuButton="true"
            />

            @if (session('status') === 'journal-responded')
                <div class="alert success">Respon tersimpan.</div>
            @endif

            <div class="card">
                <div class="section-header">
                    <div>
                        <h4>Daftar Jurnal</h4>
                        <p>Catatan reflektif yang dibagikan pengguna</p>
                    </div>
                    <form method="GET" action="{{ route('admin.journals.index') }}" class="filter-inline">
                        <select name="visibility">
                            <option value="shareable" @selected($visibility === 'shareable')>Hanya yang disetujui</option>
                            <option value="all" @selected($visibility === 'all')>Semua</option>
                        </select>
                        <button type="submit" class="ghost-button">Terapkan</button>
                    </form>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pengguna</th>
                                <th>Catatan</th>
                                <th>Status</th>
                                <th>Respon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($entries as $entry)
                                <tr>
                                    <td class="nowrap">{{ $entry->entry_date->format('d M Y') }}</td>
                                    <td>
                                        <div class="fw-600">{{ $entry->user?->name ?? 'Pengguna' }}</div>
                                        <div class="text-muted">{{ $entry->user?->email }}</div>
                                    </td>
                                    <td>{{ $entry->content }}</td>
                                    <td><span class="pill {{ $entry->is_shareable ? 'green' : 'slate' }}">{{ $entry->is_shareable ? 'Disetujui' : 'Privat' }}</span></td>
                                    <td>
                                        @if ($entry->is_shareable)
                                            <form method="POST" action="{{ route('admin.journals.update', $entry) }}" class="resp-form">
                                                @csrf @method('PATCH')
                                                <textarea name="provider_response" rows="2" placeholder="Tulis respon...">{{ old('provider_response', $entry->provider_response) }}</textarea>
                                                <button type="submit" class="ghost-button">Simpan</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="empty-row">Belum ada catatan yang dapat dimoderasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $entries->links() }}</div>
            </div>
        </main>
    </div>

    <style>
        :root { --surface: #fff; --surface-muted: #f7faf5; --border: #e2e8f0; --text: #0f172a; --muted: #64748b; --shadow: 0 12px 28px rgba(15,23,42,0.08); }
        .admin-page { background: #f4f6fb; font-family: 'Manrope', sans-serif; }
        .admin-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .admin-sidebar { background: var(--surface); padding: 28px 20px; border-right: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 24px; }
        .sidebar-brand { display: flex; gap: 12px; align-items: center; font-weight: 700; }
        .brand-icon { width: 42px; height: 42px; border-radius: 16px; background: #dff3df; display: grid; place-items: center; }
        .brand-icon span { width: 22px; height: 22px; border-radius: 999px; background: #63b96b; display: block; }
        .sidebar-brand h1 { font-size: 0.95rem; color: var(--text); }
        .sidebar-brand p { font-size: 0.8rem; color: var(--muted); }
        .sidebar-nav { display: flex; flex-direction: column; gap: 10px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px; color: var(--muted); text-decoration: none; font-size: 0.9rem; }
        .nav-icon { width: 20px; height: 20px; display: grid; place-items: center; color: currentColor; }
        .nav-icon svg { width: 18px; height: 18px; }
        .nav-item.is-active, .nav-item:hover { background: #e1f1e1; color: #256c32; }
        .sidebar-footer { margin-top: auto; }
        .footer-card { background: #f8fafc; border-radius: 16px; padding: 16px; font-size: 0.75rem; color: var(--muted); }
        .footer-title { font-weight: 700; color: var(--text); }
        .admin-main { padding: 26px 32px 48px; display: flex; flex-direction: column; gap: 8px; }
        .admin-topbar { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
        .admin-topbar > div:not(.topbar-actions) { grid-column: 2; text-align: center; }
        .admin-topbar .ghost-button { grid-column: 1; justify-self: start; }
        .admin-topbar .topbar-actions { grid-column: 3; justify-self: end; }
        .admin-topbar h2 { font-size: 1.4rem; font-weight: 700; }
        .admin-topbar p { color: var(--muted); font-size: 0.85rem; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .icon-button { position: relative; border: none; background: #fff; box-shadow: var(--shadow); border-radius: 12px; width: 38px; height: 38px; cursor: pointer; display: grid; place-items: center; color: #475569; text-decoration: none; font-size: 1rem; font-weight: 600; }
        .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .user-role { font-size: 0.75rem; color: var(--muted); }
        .chevron { color: var(--muted); }
        .card { background: var(--surface); border-radius: 20px; padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 16px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .section-header h4 { font-weight: 700; }
        .section-header p { font-size: 0.78rem; color: var(--muted); }
        .filter-inline { display: flex; gap: 8px; }
        .filter-inline select { border: 1px solid #e2e8f0; border-radius: 12px; padding: 6px 10px; font-size: 0.78rem; }
        .ghost-button { border: 1px solid #e2e8f0; background: #fff; color: #475569; border-radius: 12px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; }
        .alert { padding: 12px 16px; border-radius: 12px; font-size: 0.85rem; }
        .alert.success { background: #ecfdf3; color: #15803d; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; font-size: 0.82rem; border-collapse: collapse; }
        th { text-align: left; font-size: 0.7rem; text-transform: uppercase; color: var(--muted); padding: 8px 12px; }
        td { padding: 10px 12px; border-top: 1px solid #f1f5f9; color: var(--text); vertical-align: top; }
        td.nowrap { white-space: nowrap; }
        .fw-600 { font-weight: 600; }
        .text-muted { font-size: 0.75rem; color: var(--muted); }
        .pill { display: inline-flex; padding: 3px 10px; border-radius: 999px; font-size: 0.7rem; font-weight: 600; }
        .pill.green { background: #dcfce7; color: #15803d; }
        .pill.slate { background: #f1f5f9; color: #475569; }
        .resp-form { display: flex; flex-direction: column; gap: 6px; }
        .resp-form textarea { border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px; font-size: 0.78rem; width: 100%; }
        .empty-row { text-align: center; color: var(--muted); padding: 24px; }
        @media (max-width: 900px) {
            .admin-layout { grid-template-columns: 1fr; }
            .admin-sidebar { position: sticky; top: 0; z-index: 10; flex-direction: row; overflow-x: auto; }
            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }
            .admin-topbar { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
            .admin-topbar > div:not(.topbar-actions) { text-align: left; }
            .admin-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }
        }
    </style>
</x-app-layout>
