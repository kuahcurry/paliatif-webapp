@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $patientName = 'Bapak Supriyono';
    $patientAge = '62 Tahun';
    $patientGender = 'Laki-laki';
    $patientRm = 'No. RM: 23051567';
    $patientRoom = 'Ruang: Mawar 3';
    $patientAdmit = '10 Mei 2024';
    $patientStatus = 'Stabil';
    $patientStatusTime = 'Diperbarui: 08:45 WIB';
    $nurseName = Auth::user()->name ?? 'Siti Rahmawati';
    $nurseRole = 'Perawat';
    $writeCategories = ['Perasaan', 'Harapan', 'Doa', 'Syukur', 'Lainnya'];
    $historyCategories = ['Perasaan', 'Doa', 'Harapan', 'Syukur', 'Kekhawatiran'];
    $summaryItems = [
        ['label' => 'Syukur', 'count' => 4, 'tone' => 'good'],
        ['label' => 'Harapan', 'count' => 3, 'tone' => 'calm'],
        ['label' => 'Doa', 'count' => 3, 'tone' => 'focus'],
        ['label' => 'Perasaan', 'count' => 2, 'tone' => 'neutral'],
        ['label' => 'Kekhawatiran', 'count' => 1, 'tone' => 'warn'],
    ];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen journal-page">
    <div class="journal-layout">
        <aside class="journal-sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <span></span>
                </div>
                <div>
                    <h1>Terapi Rohani</h1>
                    <p>Pasien Paliatif</p>
                    <small>RS PKU Muhammadiyah Gombong</small>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-item" href="{{ route('dashboard') }}">Beranda</a>
                <a class="nav-item" href="#">Pasien</a>
                <a class="nav-item" href="{{ route('menu.assessment') }}">Pengkajian</a>
                <a class="nav-item" href="{{ route('menu.spiritual-needs') }}">Intervensi</a>
                <a class="nav-item" href="{{ route('menu.emotional-evaluation') }}">Evaluasi</a>

                <div class="nav-group">
                    <div class="nav-item is-active">Jurnal & Catatan</div>
                    <div class="nav-sub">
                        <a class="nav-sub-item is-active" href="{{ route('journals.index') }}">Jurnal Harian</a>
                        <a class="nav-sub-item" href="#">Catatan Keluarga</a>
                    </div>
                </div>

                <a class="nav-item" href="#">Jadwal & Aktivitas</a>
                <a class="nav-item" href="{{ route('education.index') }}">Referensi</a>
                <a class="nav-item" href="#">Laporan</a>
                <a class="nav-item" href="{{ route('profile.edit') }}">Pengaturan</a>
            </nav>

            <div class="sidebar-footer">
                <div class="footer-card">
                    <p class="footer-title">RS PKU Muhammadiyah</p>
                    <p class="footer-subtitle">Gombong</p>
                    <p class="footer-text">Melayani dengan iman, profesional dan humanis.</p>
                </div>
            </div>
        </aside>

        <main class="journal-main">
            <header class="journal-topbar">
                <button class="ghost-button" type="button" aria-label="Menu">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
                <div>
                    <h2>Jurnal Harian</h2>
                    <p>Catatan Reflektif Pasien & Keluarga</p>
                </div>
                <div class="topbar-actions">
                    <button class="icon-button" type="button" aria-label="Notifikasi">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2zm7-6V11a7 7 0 1 0-14 0v5l-2 2v1h18v-1z" fill="currentColor"/></svg>
                        <span class="badge">3</span>
                    </button>
                    <button class="icon-button" type="button" aria-label="Bantuan">?</button>
                    <div class="user-chip">
                        <div class="avatar">{{ strtoupper(substr($nurseName, 0, 1)) }}</div>
                        <div>
                            <div class="user-name">{{ $nurseName }}</div>
                            <div class="user-role">{{ $nurseRole }}</div>
                        </div>
                        <span class="chevron">v</span>
                    </div>
                </div>
            </header>

            <section class="card patient-card">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
                        <p class="muted">{{ $patientRm }} - {{ $patientRoom }}</p>
                    </div>
                </div>
                <div class="patient-metrics">
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 7h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Tanggal Masuk</p>
                            <p class="metric-value">{{ $patientAdmit }}</p>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12zm0 18a5 5 0 0 1-5-5c0-2.9 1.7-5.7 5-9 3.3 3.3 5 6.1 5 9a5 5 0 0 1-5 5z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Kondisi Umum</p>
                            <span class="status-pill good">{{ $patientStatus }}</span>
                            <p class="metric-sub">{{ $patientStatusTime }}</p>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12zm0 2.5c-4 0-7.5 2-7.5 4.5V22h15v-3c0-2.5-3.5-4.5-7.5-4.5z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Perawat Penanggung Jawab</p>
                            <p class="metric-value">{{ $nurseName }}</p>
                            <p class="metric-sub">{{ $nurseRole }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="journal-body">
                <div class="journal-left">
                    <div class="card">
                        <div class="tabs">
                            <button class="tab is-active" type="button">Jurnal Pasien</button>
                            <button class="tab" type="button">Jurnal Keluarga</button>
                        </div>

                        <div class="compose">
                            <div class="compose-header">
                                <div>
                                    <h4>Tulis Catatan Hari Ini</h4>
                                    <p>Tuliskan perasaan, harapan, doa, atau hal lain yang ingin disampaikan hari ini.</p>
                                </div>
                                <span class="privacy-pill">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l8 4v6c0 5-3.5 9.7-8 10-4.5-.3-8-5-8-10V6l8-4z" fill="currentColor"/></svg>
                                    Privasi Terlindungi
                                </span>
                            </div>

                            @if (session('status') === 'journal-saved')
                                <div class="notice success">Catatan reflektif tersimpan.</div>
                            @endif

                            @if ($errors->has('journal'))
                                <div class="notice warning">{{ $errors->first('journal') }}</div>
                            @endif

                            @if ($hasToday)
                                <div class="notice info">Catatan reflektif hari ini sudah dibuat.</div>
                            @else
                                <form method="POST" action="{{ route('journals.store') }}" class="compose-form">
                                    @csrf
                                    <textarea id="journal-content" name="content" rows="4" maxlength="1000" placeholder="Mulai tulis catatan Anda di sini...">{{ old('content') }}</textarea>
                                    @if ($errors->has('content'))
                                        <div class="field-error">{{ $errors->first('content') }}</div>
                                    @endif

                                    <div class="compose-footer">
                                        <div class="tag-row">
                                            @foreach ($writeCategories as $category)
                                                <span class="tag-chip">{{ $category }}</span>
                                            @endforeach
                                        </div>

                                        <label class="toggle">
                                            <input type="checkbox" name="is_shareable" value="1" @checked(old('is_shareable'))>
                                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                            <span>Izinkan perawat membaca catatan ini</span>
                                        </label>

                                        <div class="compose-actions">
                                            <span id="journal-counter">0/1000</span>
                                            <button type="submit" class="primary-button">Simpan Catatan</button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="history-header">
                            <div>
                                <h4>Riwayat Jurnal</h4>
                                <p>Catatan reflektif pasien dalam 7 hari terakhir.</p>
                            </div>
                            <select class="filter-select" aria-label="Filter kategori">
                                <option>Semua Kategori</option>
                                @foreach ($historyCategories as $category)
                                    <option>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if ($entries->isEmpty())
                            <p class="empty-state">Belum ada catatan reflektif.</p>
                        @else
                            <div class="history-list">
                                @foreach ($entries as $entry)
                                    @php
                                        $category = $historyCategories[$loop->index % count($historyCategories)];
                                        $statusLabel = $entry->provider_response ? 'Dibaca' : ($entry->is_shareable ? 'Belum Dibaca' : 'Privat');
                                        $statusTone = $entry->provider_response ? 'good' : ($entry->is_shareable ? 'neutral' : 'muted');
                                    @endphp
                                    <div class="history-card">
                                        <div class="date-badge">
                                            <span class="date-day">{{ $entry->entry_date->format('d') }}</span>
                                            <span class="date-month">{{ $entry->entry_date->format('M') }}</span>
                                            <span class="date-year">{{ $entry->entry_date->format('Y') }}</span>
                                        </div>
                                        <div class="history-content">
                                            <div class="history-top">
                                                <span class="category-pill">{{ $category }}</span>
                                                <span class="history-time">{{ $entry->entry_date->format('H:i') }} WIB</span>
                                            </div>
                                            <p class="history-text">{{ $entry->content }}</p>
                                            <p class="history-meta">
                                                @if ($entry->provider_response)
                                                    Dilihat oleh {{ $nurseName }}, {{ $nurseRole }}
                                                @elseif ($entry->is_shareable)
                                                    Belum dibaca oleh tenaga kesehatan
                                                @else
                                                    Catatan bersifat privat
                                                @endif
                                            </p>
                                        </div>
                                        <div class="history-actions">
                                            <span class="status-pill {{ $statusTone }}">{{ $statusLabel }}</span>
                                            <button type="button" class="icon-button ghost" aria-label="Opsi">
                                                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="5" cy="12" r="2" fill="currentColor"/><circle cx="12" cy="12" r="2" fill="currentColor"/><circle cx="19" cy="12" r="2" fill="currentColor"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($entries->hasMorePages())
                                <div class="load-more">
                                    <a class="ghost-button" href="{{ $entries->nextPageUrl() }}">Muat Lebih Banyak</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <aside class="journal-right">
                    <div class="card">
                        <div class="card-header">
                            <h4>Ringkasan Refleksi</h4>
                            <p>Ringkasan tema dalam 7 hari terakhir.</p>
                        </div>
                        <div class="summary-list">
                            @foreach ($summaryItems as $item)
                                <div class="summary-item">
                                    <div class="summary-label">
                                        <span class="summary-dot {{ $item['tone'] }}"></span>
                                        <span>{{ $item['label'] }}</span>
                                    </div>
                                    <div class="summary-bar">
                                        <span style="width: {{ min(100, $item['count'] * 20) }}%"></span>
                                    </div>
                                    <span class="summary-count">{{ $item['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card inspiration-card">
                        <div>
                            <h4>Untuk Anda Hari Ini</h4>
                            <p>Sesungguhnya bersama kesulitan ada kemudahan. (QS. Al-Insyirah: 6)</p>
                            <small>Luangkan waktu sejenak untuk menulis apa yang Anda syukuri hari ini.</small>
                        </div>
                        <div class="plant-illustration" aria-hidden="true">
                            <span class="plant-pot"></span>
                            <span class="plant-leaf"></span>
                            <span class="plant-leaf small"></span>
                        </div>
                    </div>

                    <div class="card tips-card">
                        <h4>Tips Menulis Jurnal</h4>
                        <ul>
                            <li>Tulis apa yang Anda rasakan dengan jujur.</li>
                            <li>Tidak ada jawaban benar atau salah.</li>
                            <li>Jurnal adalah ruang aman untuk Anda.</li>
                            <li>Dapat membantu proses penyembuhan batin.</li>
                        </ul>
                    </div>

                    <div class="card help-card">
                        <h4>Butuh Bantuan?</h4>
                        <p>Jika Anda merasa perlu dukungan lebih lanjut, jangan ragu untuk berbicara dengan perawat atau konselor spiritual kami.</p>
                        <button class="ghost-button" type="button">Hubungi Kami</button>
                    </div>
                </aside>
            </section>
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
            --accent-soft: #e9f6e9;
            --shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .journal-page {
            background: #f4f6fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .journal-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .journal-sidebar {
            background: var(--surface);
            padding: 28px 20px;
            border-right: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sidebar-brand {
            display: flex;
            gap: 12px;
            align-items: center;
            font-weight: 700;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            background: #dff3df;
            display: grid;
            place-items: center;
        }

        .brand-icon span {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            background: #63b96b;
            display: block;
        }

        .sidebar-brand h1 {
            font-size: 0.95rem;
            color: var(--text);
        }

        .sidebar-brand p {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .sidebar-brand small {
            font-size: 0.7rem;
            color: #94a3b8;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-radius: 12px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .nav-item.is-active,
        .nav-item:hover {
            background: #e1f1e1;
            color: #256c32;
        }

        .nav-group {
            display: grid;
            gap: 6px;
        }

        .nav-sub {
            display: grid;
            gap: 6px;
            margin-left: 12px;
        }

        .nav-sub-item {
            font-size: 0.82rem;
            color: var(--muted);
            text-decoration: none;
        }

        .nav-sub-item.is-active {
            color: #256c32;
            font-weight: 600;
        }

        .sidebar-footer {
            margin-top: auto;
        }

        .footer-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .footer-title {
            font-weight: 700;
            color: var(--text);
        }

        .footer-subtitle {
            margin-bottom: 6px;
        }

        .journal-main {
            padding: 26px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .journal-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .journal-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .journal-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .ghost-button {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-button {
            position: relative;
            border: none;
            background: #ffffff;
            box-shadow: var(--shadow);
            border-radius: 12px;
            width: 38px;
            height: 38px;
            cursor: pointer;
            display: grid;
            place-items: center;
            color: #475569;
        }

        .icon-button.ghost {
            box-shadow: none;
            border: 1px solid #e2e8f0;
        }

        .icon-button svg {
            width: 20px;
            height: 20px;
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #22c55e;
            color: #ffffff;
            font-size: 0.65rem;
            width: 18px;
            height: 18px;
            border-radius: 999px;
            display: grid;
            place-items: center;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ffffff;
            border-radius: 16px;
            padding: 6px 12px;
            box-shadow: var(--shadow);
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #1d4ed8;
            font-weight: 700;
            display: grid;
            place-items: center;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .patient-card {
            display: grid;
            grid-template-columns: 1.2fr 2fr;
            gap: 20px;
            align-items: center;
        }

        .patient-profile {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .patient-avatar {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #e2f4e2;
            display: grid;
            place-items: center;
            font-size: 1.4rem;
            font-weight: 700;
            color: #2f855a;
        }

        .muted {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .patient-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .metric {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
            background: #f9fafb;
            border-radius: 16px;
            padding: 12px;
        }

        .metric-icon {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: #e5f6e5;
            display: grid;
            place-items: center;
            color: #3f7a3f;
        }

        .metric-icon svg {
            width: 18px;
            height: 18px;
        }

        .metric-label {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .metric-value {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text);
        }

        .metric-sub {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
        }

        .status-pill.neutral {
            background: #e2e8f0;
            color: #475569;
        }

        .status-pill.muted {
            background: #f1f5f9;
            color: #94a3b8;
        }

        .journal-body {
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            gap: 20px;
        }

        .tabs {
            display: flex;
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .tab {
            border: none;
            background: transparent;
            font-weight: 600;
            color: var(--muted);
            padding-bottom: 10px;
            cursor: pointer;
        }

        .tab.is-active {
            color: #256c32;
            border-bottom: 2px solid #256c32;
        }

        .compose {
            display: grid;
            gap: 16px;
        }

        .compose-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .compose-header h4 {
            font-weight: 700;
        }

        .compose-header p {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .privacy-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            color: #16a34a;
            border-radius: 999px;
            font-size: 0.7rem;
            padding: 6px 10px;
            font-weight: 600;
        }

        .privacy-pill svg {
            width: 14px;
            height: 14px;
        }

        .compose-form {
            display: grid;
            gap: 12px;
        }

        .compose-form textarea {
            width: 100%;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 12px;
            font-size: 0.9rem;
            min-height: 120px;
            background: #fbfdfb;
        }

        .field-error {
            font-size: 0.75rem;
            color: #dc2626;
        }

        .notice {
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.8rem;
        }

        .notice.success { background: #e0f2fe; color: #0369a1; }
        .notice.warning { background: #fef3c7; color: #92400e; }
        .notice.info { background: #ecfccb; color: #3f6212; }

        .compose-footer {
            display: grid;
            gap: 12px;
        }

        .tag-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag-chip {
            background: #f1f5f9;
            color: #475569;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
        }

        .toggle {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .toggle input { display: none; }

        .toggle-track {
            width: 38px;
            height: 20px;
            background: #e2e8f0;
            border-radius: 999px;
            position: relative;
            transition: all 0.2s ease;
        }

        .toggle-thumb {
            width: 16px;
            height: 16px;
            background: #fff;
            border-radius: 999px;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
        }

        .toggle input:checked + .toggle-track {
            background: #4f9b4f;
        }

        .toggle input:checked + .toggle-track .toggle-thumb {
            left: 20px;
        }

        .compose-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .primary-button {
            background: #4f9b4f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .history-header h4 {
            font-weight: 700;
        }

        .history-header p {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .filter-select {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 6px 10px;
            font-size: 0.75rem;
        }

        .empty-state {
            font-size: 0.85rem;
            color: var(--muted);
        }

        .history-list {
            display: grid;
            gap: 12px;
        }

        .history-card {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 12px;
            align-items: center;
            background: #f8fafc;
            border-radius: 16px;
            padding: 12px;
        }

        .date-badge {
            width: 70px;
            border-radius: 14px;
            background: #f1f5f9;
            display: grid;
            place-items: center;
            padding: 10px 6px;
            text-align: center;
            color: #1f2937;
            font-weight: 600;
        }

        .date-day { font-size: 1.1rem; }
        .date-month { font-size: 0.75rem; text-transform: uppercase; }
        .date-year { font-size: 0.7rem; color: var(--muted); }

        .history-content {
            display: grid;
            gap: 6px;
        }

        .history-top {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .category-pill {
            background: #dcfce7;
            color: #166534;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 999px;
            font-weight: 600;
        }

        .history-time {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .history-text {
            font-size: 0.85rem;
            color: #1f2937;
        }

        .history-meta {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .history-actions {
            display: grid;
            gap: 8px;
            align-items: center;
            justify-items: end;
        }

        .load-more {
            margin-top: 10px;
            display: flex;
            justify-content: center;
        }

        .summary-list {
            display: grid;
            gap: 10px;
        }

        .summary-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 10px;
        }

        .summary-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
        }

        .summary-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #22c55e;
        }

        .summary-dot.calm { background: #16a34a; }
        .summary-dot.focus { background: #15803d; }
        .summary-dot.neutral { background: #94a3b8; }
        .summary-dot.warn { background: #f97316; }

        .summary-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .summary-bar span {
            display: block;
            height: 100%;
            background: #22c55e;
            border-radius: 999px;
        }

        .summary-count {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .inspiration-card {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            background: #f0fdf4;
        }

        .inspiration-card p {
            font-size: 0.8rem;
            color: #166534;
        }

        .inspiration-card small {
            color: #15803d;
        }

        .plant-illustration {
            width: 70px;
            height: 70px;
            position: relative;
        }

        .plant-pot {
            position: absolute;
            bottom: 0;
            left: 10px;
            width: 50px;
            height: 28px;
            background: #f97316;
            border-radius: 6px 6px 12px 12px;
        }

        .plant-leaf {
            position: absolute;
            bottom: 20px;
            left: 30px;
            width: 18px;
            height: 30px;
            background: #22c55e;
            border-radius: 999px 999px 0 999px;
            transform: rotate(-20deg);
        }

        .plant-leaf.small {
            left: 15px;
            height: 22px;
            transform: rotate(15deg);
            background: #16a34a;
        }

        .tips-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 8px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .help-card {
            background: #f1f5f9;
        }

        @media (max-width: 1200px) {
            .patient-card {
                grid-template-columns: 1fr;
            }

            .patient-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .journal-body {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .journal-layout {
                grid-template-columns: 1fr;
            }

            .journal-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                flex-direction: row;
                overflow-x: auto;
                gap: 12px;
            }

            .sidebar-brand,
            .sidebar-footer {
                display: none;
            }

            .sidebar-nav {
                flex-direction: row;
                flex-wrap: nowrap;
            }

            .nav-group {
                min-width: 180px;
            }

            .journal-topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <script>
        const journalInput = document.getElementById('journal-content');
        const journalCounter = document.getElementById('journal-counter');
        if (journalInput && journalCounter) {
            const updateCounter = () => {
                journalCounter.textContent = journalInput.value.length + '/1000';
            };
            updateCounter();
            journalInput.addEventListener('input', updateCounter);
        }
    </script>
</x-app-layout>
