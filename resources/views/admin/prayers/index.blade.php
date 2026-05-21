@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
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
                                    <td class="nowrap" data-label="Tanggal">{{ $prayer->created_at->format('d M Y') }}</td>
                                    <td data-label="Nama">{{ $prayer->display_name ?: ($prayer->user?->name ?? 'Anonim') }}</td>
                                    <td data-label="Kategori">
                                        @if ($prayer->category === 'self') Untuk diri sendiri
                                        @elseif ($prayer->category === 'others') Untuk orang lain
                                        @else Doa syukur @endif
                                    </td>
                                    <td data-label="Status"><span class="pill {{ $prayer->is_public ? 'green' : 'slate' }}">{{ $prayer->is_public ? 'Publik' : 'Privat' }}</span></td>
                                    <td data-label="Dukungan">{{ $prayer->support_count }}</td>
                                    <td class="truncate" data-label="Isi">{{ Str::limit($prayer->content, 80) }}</td>
                                    <td data-label="Aksi">
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

    @include('admin.partials.admin-styles')
</x-app-layout>
