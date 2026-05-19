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
                title="Moderasi Jurnal"
                subtitle="Kelola catatan reflektif pengguna"
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
                                    <td class="nowrap" data-label="Tanggal">{{ $entry->entry_date->format('d M Y') }}</td>
                                    <td data-label="Pengguna">
                                        <div class="fw-600">{{ $entry->user?->name ?? 'Pengguna' }}</div>
                                        <div class="text-muted">{{ $entry->user?->email }}</div>
                                    </td>
                                    <td data-label="Catatan">{{ $entry->content }}</td>
                                    <td data-label="Status"><span class="pill {{ $entry->is_shareable ? 'green' : 'slate' }}">{{ $entry->is_shareable ? 'Disetujui' : 'Privat' }}</span></td>
                                    <td data-label="Respon">
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

    @include('admin.partials.admin-styles')
</x-app-layout>
