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
                title="Moderasi Doa"
                subtitle="Kelola doa pengguna"
                :showMenuButton="true"
            />

            @if (session('status') === 'prayer-deleted')
                <div class="alert warn">Doa berhasil dihapus.</div>
            @endif

            <div class="card">
                <div class="section-header">
                    <div>
                        <h4>Daftar Doa</h4>
                        <p>Doa yang dikirimkan pengguna</p>
                    </div>
                    <form method="GET" action="{{ route('admin.prayers.index') }}" class="filter-inline">
                        <select name="visibility">
                            <option value="all" @selected($visibility === 'all')>Semua</option>
                            <option value="public" @selected($visibility === 'public')>Publik</option>
                            <option value="private" @selected($visibility === 'private')>Privat</option>
                        </select>
                        <select name="category">
                            <option value="all" @selected($category === 'all')>Semua Kategori</option>
                            <option value="self" @selected($category === 'self')>Untuk diri sendiri</option>
                            <option value="others" @selected($category === 'others')>Untuk orang lain</option>
                            <option value="gratitude" @selected($category === 'gratitude')>Doa syukur</option>
                        </select>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Cari...">
                        <button type="submit" class="ghost-button">Terapkan</button>
                    </form>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Dukungan</th>
                                <th>Isi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($prayers as $prayer)
                                <tr>
                                    <td>{{ $prayer->created_at->format('d M Y') }}</td>
                                    <td>{{ $prayer->display_name ?: ($prayer->user?->name ?? 'Anonim') }}</td>
                                    <td>
                                        @if ($prayer->category === 'self') Untuk diri sendiri
                                        @elseif ($prayer->category === 'others') Untuk orang lain
                                        @else Doa syukur @endif
                                    </td>
                                    <td><span class="pill {{ $prayer->is_public ? 'green' : 'slate' }}">{{ $prayer->is_public ? 'Publik' : 'Privat' }}</span></td>
                                    <td>{{ $prayer->support_count }}</td>
                                    <td class="truncate">{{ Str::limit($prayer->content, 80) }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.prayers.destroy', $prayer) }}" onsubmit="return confirm('Yakin akan menghapus doa ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="danger-button">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="empty-row">Belum ada doa yang masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $prayers->links() }}</div>
            </div>
        </main>
    </div>

    <style>
        :root { --surface: #fff; --surface-muted: #f7faf5; --border: #e2e8f0; --text: #0f172a; --muted: #64748b; --accent: #4f9b4f; --shadow: 0 12px 28px rgba(15,23,42,0.08); }
        .admin-page { background: #f4f6fb; font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif; }
        .admin-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .admin-sidebar { background: var(--surface); padding: 28px 20px; border-right: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 24px; }
        .sidebar-brand { display: flex; gap: 12px; align-items: center; font-weight: 700; }
        .brand-icon { width: 42px; height: 42px; border-radius: 16px; background: #dff3df; display: grid; place-items: center; }
        .brand-icon span { width: 22px; height: 22px; border-radius: 999px; background: #63b96b; display: block; }
        .sidebar-brand h1 { font-size: 0.95rem; color: var(--text); }
        .sidebar-brand p { font-size: 0.8rem; color: var(--muted); }
        .sidebar-nav { display: flex; flex-direction: column; gap: 8px; }
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
        .icon-button svg { width: 20px; height: 20px; }
        .badge { position: absolute; top: -4px; right: -4px; background: #22c55e; color: #fff; font-size: 0.65rem; width: 18px; height: 18px; border-radius: 999px; display: grid; place-items: center; }
        .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); text-align: left; }
        .user-role { font-size: 0.75rem; color: var(--muted); text-align: left; }
        .chevron { color: var(--muted); }
        .card { background: var(--surface); border-radius: 20px; padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 16px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
        .section-header h4 { font-weight: 700; }
        .section-header p { font-size: 0.78rem; color: var(--muted); }
        .filter-inline { display: flex; gap: 8px; flex-wrap: wrap; }
        .filter-inline select, .filter-inline input { border: 1px solid #e2e8f0; border-radius: 12px; padding: 6px 10px; font-size: 0.78rem; }
        .ghost-button { border: 1px solid #e2e8f0; background: #fff; color: #475569; border-radius: 12px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; text-decoration: none; }
        .alert { padding: 12px 16px; border-radius: 12px; font-size: 0.85rem; }
        .alert.warn { background: #fff7ed; color: #92400e; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; font-size: 0.82rem; border-collapse: collapse; }
        th { text-align: left; font-size: 0.7rem; text-transform: uppercase; color: var(--muted); padding: 8px 12px; }
        td { padding: 10px 12px; border-top: 1px solid #f1f5f9; color: var(--text); }
        td.truncate { max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .empty-row { text-align: center; color: var(--muted); padding: 24px; }
        .pill { display: inline-flex; padding: 3px 10px; border-radius: 999px; font-size: 0.7rem; font-weight: 600; }
        .pill.green { background: #dcfce7; color: #15803d; }
        .pill.slate { background: #f1f5f9; color: #475569; }
        .danger-button { background: none; border: 1px solid #fca5a5; color: #dc2626; border-radius: 10px; padding: 4px 12px; font-size: 0.75rem; cursor: pointer; }
        @media (max-width: 900px) {
            .admin-layout { grid-template-columns: 1fr; }
            .admin-sidebar { position: sticky; top: 0; z-index: 10; flex-direction: row; overflow-x: auto; gap: 12px; }
            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }
            .admin-topbar { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
            .admin-topbar > div:not(.topbar-actions) { text-align: left; }
            .admin-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }
        }
    </style>
</x-app-layout>
