@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $_user = Auth::user();
    $patientName = $_user->name ?? 'Pasien';
    $patientAge = $_user->patient_age ? $_user->patient_age . ' Tahun' : '--';
    $patientGender = $_user->patient_gender ?? '--';

    $patientAdmit = $_user->created_at ? $_user->created_at->format('d M Y') : '--';
    $patientStatus = $patientStatus ?? 'Dalam Pemantauan';
    $patientStatusTime = 'Diperbarui: ' . now()->format('H:i') . ' WIB';

    $writeCategories = $writeCategories ?? ['Perasaan', 'Harapan', 'Doa', 'Syukur', 'Kekhawatiran', 'Lainnya'];
    $historyCategories = $historyCategories ?? $writeCategories;
    $summaryItems = $summaryItems ?? [];
    $entryTypes = $entryTypes ?? [
        'patient' => 'Jurnal Pasien',
        'family' => 'Jurnal Keluarga',
    ];
    $activeEntryType = $activeEntryType ?? 'patient';
    $activeCategory = $activeCategory ?? 'all';
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen journal-page">
    <div class="journal-layout">
        <aside class="journal-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="journal-main">
            <x-app-topbar
                class="journal-topbar"
                title="Jurnal Harian"
                subtitle="Catatan Reflektif Pasien & Keluarga"
            />
            <section class="journal-body">
                <div class="journal-left">
                    <div class="card">
                        <div class="tabs" data-tabs>
                            @foreach ($entryTypes as $typeValue => $typeLabel)
                                <button class="tab {{ $activeEntryType === $typeValue ? 'is-active' : '' }}" type="button" data-entry-type="{{ $typeValue }}">
                                    {{ $typeLabel }}
                                </button>
                            @endforeach
                        </div>

                        <div class="compose">
                            <div class="compose-header">
                                <div>
                                    <h4>Tulis Catatan Hari Ini</h4>
                                    <p>Tuliskan perasaan, harapan, doa, atau hal lain yang ingin disampaikan hari ini.</p>
                                </div>
                                <span class="privacy-pill">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
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
                                <form method="POST" action="{{ route('journals.store') }}" class="compose-form" data-compose-form>
                                    @csrf
                                    <input type="hidden" name="entry_type" id="entry-type" value="{{ $activeEntryType }}">
                                    <input type="hidden" name="category" id="entry-category" value="">
                                    <textarea id="journal-content" name="content" rows="4" maxlength="1000" placeholder="Mulai tulis catatan Anda di sini...">{{ old('content') }}</textarea>
                                    @if ($errors->has('content'))
                                        <div class="field-error">{{ $errors->first('content') }}</div>
                                    @endif

                                    <div class="compose-footer">
                                        <div class="tag-row">
                                            @foreach ($writeCategories as $category)
                                                <button class="tag-chip" type="button" data-category="{{ $category }}">{{ $category }}</button>
                                            @endforeach
                                        </div>

                                        <label class="toggle">
                                            <input type="checkbox" name="is_shareable" value="1" @checked(old('is_shareable'))>
                                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                                            <span>Izinkan tenaga kesehatan membaca catatan ini</span>
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
                            <form method="GET" action="{{ route('journals.index') }}" class="filter-form">
                                <input type="hidden" name="entry_type" value="{{ $activeEntryType }}">
                                <select class="filter-select" name="category" aria-label="Filter kategori" data-filter-select>
                                    <option value="all" @selected($activeCategory === 'all')>Semua Kategori</option>
                                    @foreach ($historyCategories as $category)
                                        <option value="{{ $category }}" @selected($activeCategory === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        @if ($entries->isEmpty())
                            <p class="empty-state">Belum ada catatan reflektif.</p>
                        @else
                            <div class="history-list">
                                @foreach ($entries as $entry)
                                    @php
                                        $category = $entry->category ?: 'Lainnya';
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
                                                <span class="history-time">{{ $entry->created_at->format('H:i') }} WIB</span>
                                            </div>
                                            <p class="history-text">{{ $entry->content }}</p>
                                            <p class="history-meta">
                                                @if ($entry->provider_response)
                                                    Telah direspon oleh tenaga kesehatan
                                                @elseif ($entry->is_shareable)
                                                    Belum dibaca oleh tenaga kesehatan
                                                @else
                                                    Catatan bersifat privat
                                                @endif
                                            </p>
                                        </div>
                                        <div class="history-actions">
                                            <span class="status-pill {{ $statusTone }}">{{ $statusLabel }}</span>

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
                            <a href="{{ route('faq') }}" class="ghost-button">Hubungi Kami</a>
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
            gap: 10px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 12px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: grid;
            place-items: center;
            color: currentColor;
        }

        .nav-icon svg {
            width: 18px;
            height: 18px;
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
            gap: 8px;
        }

        .journal-left,
        .journal-right {
            display: grid;
            gap: 8px;
        }

        .journal-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .journal-topbar > div:not(.topbar-actions) {
            grid-column: 2;
            text-align: center;
        }

        .journal-topbar .ghost-button {
            grid-column: 1;
            justify-self: start;
        }

        .journal-topbar .topbar-actions {
            grid-column: 3;
            justify-self: end;
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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tag-chip:hover {
            background: #e2e8f0;
        }

        .tag-chip.is-active {
            background: #4f9b4f;
            color: #ffffff;
            border-color: #4f9b4f;
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
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .journal-topbar > div:not(.topbar-actions) {
                text-align: left;
            }

            .journal-topbar .topbar-actions {
                justify-self: auto;
                align-self: flex-start;
            }
        }
    </style>

    <script>
        const journalInput = document.getElementById('journal-content');
        const journalCounter = document.getElementById('journal-counter');
        const entryTypeInput = document.getElementById('entry-type');
        const entryCategoryInput = document.getElementById('entry-category');
        const tabs = document.querySelectorAll('[data-tabs] .tab');
        const categoryButtons = document.querySelectorAll('[data-category]');
        const filterSelect = document.querySelector('[data-filter-select]');

        const setActiveCategory = (value) => {
            if (entryCategoryInput) {
                entryCategoryInput.value = value;
            }
            categoryButtons.forEach((button) => {
                button.classList.toggle('is-active', button.dataset.category === value);
            });
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const entryType = tab.dataset.entryType || 'patient';
                if (entryTypeInput) {
                    entryTypeInput.value = entryType;
                }
                tabs.forEach((item) => item.classList.toggle('is-active', item === tab));
                setActiveCategory('');
            });
        });

        categoryButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                setActiveCategory(button.dataset.category || '');
            });
        });

        if (journalInput && journalCounter) {
            const updateCounter = () => {
                journalCounter.textContent = journalInput.value.length + '/1000';
            };
            updateCounter();
            journalInput.addEventListener('input', updateCounter);
        }

        if (filterSelect) {
            filterSelect.addEventListener('change', () => {
                const form = filterSelect.closest('form');
                if (form) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
