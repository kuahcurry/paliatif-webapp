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
                title="Evaluasi Perasaan"
                subtitle="Lihat hasil evaluasi pengguna"
            />

            <div class="card">
                <div class="section-header">
                    <div>
                        <h4>Riwayat Evaluasi</h4>
                        <p>Semua data evaluasi perasaan dari pengguna</p>
                    </div>
                    <form method="GET" action="{{ route('admin.evaluations.index') }}" class="filter-inline">
                        <select name="emotion">
                            <option value="all" @selected($emotion === 'all')>Semua Emosi</option>
                            <option value="very_calm" @selected($emotion === 'very_calm')>Sangat Tenang</option>
                            <option value="calm" @selected($emotion === 'calm')>Tenang</option>
                            <option value="neutral" @selected($emotion === 'neutral')>Biasa Saja</option>
                            <option value="anxious_sad" @selected($emotion === 'anxious_sad')>Cemas / Sedih</option>
                            <option value="distressed" @selected($emotion === 'distressed')>Sangat Tertekan</option>
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
                                <th>Sesi</th>
                                <th>Emosi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $emotionLabels = ['very_calm' => 'Sangat Tenang', 'calm' => 'Tenang', 'neutral' => 'Biasa Saja', 'anxious_sad' => 'Cemas / Sedih', 'distressed' => 'Sangat Tertekan'];
                                $emotionColors = ['very_calm' => 'green', 'calm' => 'green', 'neutral' => 'slate', 'anxious_sad' => 'amber', 'distressed' => 'rose'];
                            @endphp
                            @forelse ($evaluations as $evaluation)
                                <tr>
                                    <td class="nowrap" data-label="Tanggal">{{ $evaluation->created_at->format('d M Y H:i') }}</td>
                                    <td data-label="Pengguna">
                                        <div class="fw-600">{{ $evaluation->user?->name ?? 'Pengguna' }}</div>
                                        <div class="text-muted">{{ $evaluation->user?->email }}</div>
                                    </td>
                                    <td data-label="Sesi">{{ $evaluation->session_number }}/5</td>
                                    <td data-label="Emosi">
                                        <span class="pill {{ $emotionColors[$evaluation->emotion] ?? 'slate' }}">
                                            {{ $emotionLabels[$evaluation->emotion] ?? $evaluation->emotion }}
                                        </span>
                                    </td>
                                    <td class="text-muted" data-label="Catatan">{{ $evaluation->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="empty-row">Belum ada evaluasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $evaluations->links() }}</div>
            </div>
        </main>
    </div>

    @include('admin.partials.admin-styles')
</x-app-layout>
