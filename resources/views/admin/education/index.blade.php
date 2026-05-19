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
                title="Modul Edukasi"
                subtitle="Kelola konten edukasi"
            />

            @if (session('status') === 'module-created')
                <div class="alert success">Modul berhasil dibuat.</div>
            @endif
            @if (session('status') === 'module-updated')
                <div class="alert success">Modul berhasil diperbarui.</div>
            @endif
            @if (session('status') === 'module-deleted')
                <div class="alert warn">Modul berhasil dihapus.</div>
            @endif

            <div class="card">
                <div class="section-header">
                    <div>
                        <h4>Daftar Modul</h4>
                        <p>Semua modul edukasi yang tersedia</p>
                    </div>
                    <div class="header-actions">
                        <form method="GET" action="{{ route('admin.education.index') }}" class="filter-inline">
                            <select name="type">
                                <option value="all" @selected($type === 'all')>Semua Tipe</option>
                                <option value="article" @selected($type === 'article')>Artikel</option>
                                <option value="video" @selected($type === 'video')>Video</option>
                            </select>
                            <button type="submit" class="ghost-button">Terapkan</button>
                        </form>
                        <a href="{{ route('admin.education.create') }}" class="primary-button">+ Tambah Modul</a>
                    </div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>Tag</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($modules as $module)
                                <tr>
                                    <td data-label="Judul">
                                        <div class="fw-600">{{ $module->title }}</div>
                                        <div class="text-muted">{{ Str::limit($module->summary, 80) }}</div>
                                    </td>
                                    <td data-label="Tipe"><span class="pill {{ $module->type === 'video' ? 'purple' : 'green' }}">{{ $module->type === 'video' ? 'Video' : 'Artikel' }}</span></td>
                                    <td data-label="Tag">
                                        <div class="tags">
                                            @foreach ($module->tags ?? [] as $tag)
                                                <span class="tag">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td data-label="Status"><span class="pill {{ $module->is_active ? 'green' : 'slate' }}">{{ $module->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                    <td class="text-muted" data-label="Tanggal">{{ $module->created_at->format('d M Y') }}</td>
                                    <td data-label="Aksi">
                                        <div class="action-group">
                                            <a href="{{ route('admin.education.edit', $module) }}" class="ghost-button">Edit</a>
                                            <form method="POST" action="{{ route('admin.education.destroy', $module) }}" onsubmit="return confirm('Yakin akan menghapus modul ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="danger-button">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="empty-row">Belum ada modul edukasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $modules->links() }}</div>
            </div>
        </main>
    </div>

    @include('admin.partials.admin-styles')
</x-app-layout>
