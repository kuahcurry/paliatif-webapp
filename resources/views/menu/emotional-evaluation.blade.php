@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $patientName = Auth::user()->name ?? 'Bapak Supriyono';
    $patientAge = '62 Tahun';
    $patientGender = 'Laki-laki';
    $patientRm = 'No. RM: 23051567';
    $patientRoom = 'Ruang: Mawar 3';
    $nurseName = Auth::user()->name ?? 'Siti Rahmawati';
    $nurseRole = 'Perawat';
    $sessionCount = 2;
    $maxSessions = 5;
    $lastService = 'Harapan & Makna Hidup';
    $lastServiceDate = now()->format('d M Y, H:i') . ' WIB';
    $historyItems = [
        ['label' => 'Sangat Tenang', 'date' => '15 Mei 2024, 10:15 WIB', 'status' => 'Sesi 1', 'tone' => 'good'],
        ['label' => 'Tenang', 'date' => '16 Mei 2024, 09:30 WIB', 'status' => 'Sesi 2', 'tone' => 'calm'],
        ['label' => 'Belum dievaluasi', 'date' => '-', 'status' => 'Sesi 3', 'tone' => 'neutral'],
        ['label' => 'Belum dievaluasi', 'date' => '-', 'status' => 'Sesi 4', 'tone' => 'neutral'],
        ['label' => 'Belum dievaluasi', 'date' => '-', 'status' => 'Sesi 5', 'tone' => 'neutral'],
    ];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen dashboard-page">
    <div class="dashboard-layout">
        <aside class="dashboard-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="dashboard-main">
            <x-app-topbar
                class="dashboard-topbar"
                title="Evaluasi Perasaan"
                subtitle="Terapi Rohani Pasien Paliatif"
                :badgeCount="3"
            />

            <section class="card evaluation-hero">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
                        <p class="muted">{{ $patientRm }} - {{ $patientRoom }}</p>
                    </div>
                </div>
                <div class="hero-metrics">
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2v3M17 2v3M4 7h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Sesi Layanan ke</p>
                            <p class="metric-value">{{ $sessionCount }} dari {{ $maxSessions }}</p>
                            <p class="metric-sub">Gunakan layanan hingga 5 kali</p>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-6-3.5-8-7.5C2 8.5 5 6 8 6c1.7 0 3.2 1 4 2.3C12.8 7 14.3 6 16 6c3 0 6 2.5 4 7.5-2 4-8 7.5-8 7.5z" fill="currentColor"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Layanan Terakhir</p>
                            <p class="metric-value">{{ $lastService }}</p>
                            <p class="metric-sub">{{ $lastServiceDate }}</p>
                            <a class="metric-link" href="#">Lihat Detail</a>
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

            <section class="evaluation-content">
                <div class="evaluation-main">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4>Bagaimana perasaan Anda saat ini?</h4>
                                <p>Pilih gambar yang paling menggambarkan perasaan Anda setelah menggunakan layanan spiritual.</p>
                            </div>
                        </div>

                        <div class="emotion-grid">
                            <div class="emotion-card selected">
                                <div class="face face-happy">
                                    <span class="eye left"></span>
                                    <span class="eye right"></span>
                                    <span class="mouth"></span>
                                </div>
                                <h5>Sangat Tenang</h5>
                                <p>Merasa sangat damai, penuh harapan dan bersyukur.</p>
                            </div>
                            <div class="emotion-card">
                                <div class="face face-calm">
                                    <span class="eye left"></span>
                                    <span class="eye right"></span>
                                    <span class="mouth"></span>
                                </div>
                                <h5>Tenang</h5>
                                <p>Merasa lebih tenang dan nyaman dari sebelumnya.</p>
                            </div>
                            <div class="emotion-card">
                                <div class="face face-neutral">
                                    <span class="eye left"></span>
                                    <span class="eye right"></span>
                                    <span class="mouth"></span>
                                </div>
                                <h5>Biasa Saja</h5>
                                <p>Belum ada perubahan yang signifikan dalam perasaan saya.</p>
                            </div>
                            <div class="emotion-card">
                                <div class="face face-anxious">
                                    <span class="eye left"></span>
                                    <span class="eye right"></span>
                                    <span class="mouth"></span>
                                </div>
                                <h5>Cemas / Sedih</h5>
                                <p>Masih merasa cemas, sedih, atau khawatir dengan kondisi saya.</p>
                            </div>
                            <div class="emotion-card">
                                <div class="face face-distress">
                                    <span class="eye left"></span>
                                    <span class="eye right"></span>
                                    <span class="mouth"></span>
                                </div>
                                <h5>Sangat Tertekan</h5>
                                <p>Merasa sangat tertekan, putus asa atau kehilangan harapan.</p>
                            </div>
                        </div>

                        <div class="note-section">
                            <label for="evaluation-note">Catatan Tambahan (Opsional)</label>
                            <textarea id="evaluation-note" rows="3" placeholder="Tuliskan perasaan atau hal yang ingin Anda sampaikan..."></textarea>
                            <div class="note-footer">
                                <span id="note-counter">0/300</span>
                                <button type="button" class="primary-button">Simpan Evaluasi</button>
                            </div>
                        </div>
                    </div>

                    <div class="card tips-card">
                        <div class="tips-content">
                            <h5>Tips untuk Anda</h5>
                            <p>Luangkan waktu sejenak untuk berdoa, berdzikir, atau merenung sesuai keyakinan Anda. Jangan ragu untuk berbagi perasaan dengan tim pendamping.</p>
                        </div>
                        <div class="tips-quote">
                            <span>Dan hanya kepada Tuhanmulah hendaknya kamu berharap.</span>
                            <small>(QS. Al-Insyirah: 8)</small>
                        </div>
                    </div>
                </div>

                <aside class="evaluation-side">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h4>Progres Evaluasi</h4>
                                <p>Anda dapat menggunakan layanan hingga 5 kali.</p>
                            </div>
                        </div>

                        <div class="progress-line">
                            @for ($i = 1; $i <= $maxSessions; $i++)
                                <div class="progress-step {{ $i < $sessionCount ? 'done' : ($i === $sessionCount ? 'current' : '') }}">
                                    <span>{{ $i }}</span>
                                </div>
                            @endfor
                        </div>

                        <div class="history-list">
                            <h5>Riwayat Evaluasi Anda</h5>
                            @foreach ($historyItems as $item)
                                <div class="history-item">
                                    <span class="history-dot {{ $item['tone'] }}"></span>
                                    <div>
                                        <p class="history-title">{{ $item['label'] }}</p>
                                        <p class="history-date">{{ $item['date'] }}</p>
                                    </div>
                                    <span class="history-tag">{{ $item['status'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card warning-card">
                        <h4>Belum Ada Perubahan Setelah 5 Sesi?</h4>
                        <p>Jika setelah 5 sesi layanan tidak ada perubahan yang dirasakan dan Anda membutuhkan bantuan lebih lanjut, kami akan membantu menghubungkan Anda dengan layanan profesional terkait.</p>
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
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .dashboard-page {
            background: #f5f7fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .dashboard-sidebar {
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
            width: 40px;
            height: 40px;
            border-radius: 14px;
            background: #dff3df;
            display: grid;
            place-items: center;
        }

        .brand-icon span {
            width: 20px;
            height: 20px;
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

        .nav-item.is-active,
        .nav-item:hover {
            background: #e1f1e1;
            color: #256c32;
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

        .nav-group {
            display: grid;
            gap: 8px;
        }

        .nav-sub {
            display: grid;
            gap: 6px;
            margin-left: 34px;
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
            background: #ffffff;
            border-radius: 16px;
            padding: 16px;
            font-size: 0.75rem;
            color: var(--muted);
            box-shadow: var(--shadow);
        }

        .footer-title {
            font-weight: 700;
            color: var(--text);
        }

        .footer-subtitle {
            margin-bottom: 6px;
        }

        .dashboard-main {
            padding: 28px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .dashboard-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .dashboard-topbar > div:first-child {
            grid-column: 2;
            text-align: center;
        }

        .dashboard-topbar .topbar-actions {
            grid-column: 3;
            justify-self: end;
        }

        .dashboard-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .dashboard-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
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

        .icon-button svg {
            width: 20px;
            height: 20px;
        }

        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
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
            background: #dbeafe;
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

        .evaluation-hero {
            display: grid;
            grid-template-columns: 1.1fr 2fr;
            gap: 20px;
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

        .hero-metrics {
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

        .metric-link {
            font-size: 0.7rem;
            color: #2f855a;
        }

        .evaluation-content {
            display: grid;
            grid-template-columns: 2.1fr 1fr;
            gap: 20px;
        }

        .evaluation-main,
        .evaluation-side {
            display: grid;
            gap: 8px;
        }

        .card-header h4 {
            font-weight: 700;
            font-size: 1rem;
        }

        .card-header p {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .emotion-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 12px;
        }

        .emotion-card {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 12px;
            display: grid;
            gap: 8px;
            text-align: center;
            background: #fff;
        }

        .emotion-card.selected {
            border-color: #86efac;
            box-shadow: 0 12px 20px rgba(34, 197, 94, 0.12);
        }

        .emotion-card h5 {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .emotion-card p {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .face {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
            background: #bbf7d0;
        }

        .face .eye {
            width: 8px;
            height: 8px;
            background: #1f2937;
            border-radius: 999px;
            position: absolute;
            top: 24px;
        }

        .face .eye.left { left: 20px; }
        .face .eye.right { right: 20px; }

        .face .mouth {
            position: absolute;
            width: 26px;
            height: 12px;
            border: 3px solid #1f2937;
            border-color: transparent transparent #1f2937 transparent;
            border-radius: 0 0 20px 20px;
            bottom: 18px;
            left: 22px;
        }

        .face-calm { background: #d9f99d; }
        .face-neutral { background: #fde68a; }
        .face-anxious { background: #fdba74; }
        .face-distress { background: #fca5a5; }

        .face-neutral .mouth {
            height: 0;
            border-color: #1f2937 transparent transparent transparent;
            top: 40px;
        }

        .face-anxious .mouth,
        .face-distress .mouth {
            height: 0;
            border-color: #1f2937 transparent transparent transparent;
            top: 42px;
        }

        .note-section {
            display: grid;
            gap: 8px;
            margin-top: 12px;
        }

        .note-section label {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .note-section textarea {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        .note-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .tips-card {
            display: flex;
            flex-direction: row;
            gap: 16px;
            align-items: center;
            justify-content: space-between;
            background: #f0fdf4;
        }

        .tips-content h5 {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .tips-content p {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .tips-quote {
            text-align: right;
            font-size: 0.75rem;
            color: #15803d;
        }

        .tips-quote small {
            display: block;
            color: #166534;
            margin-top: 6px;
        }

        .progress-line {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
            align-items: center;
        }

        .progress-step {
            height: 28px;
            border-radius: 999px;
            background: #f1f5f9;
            display: grid;
            place-items: center;
            font-size: 0.75rem;
            color: var(--muted);
        }

        .progress-step.done {
            background: #bbf7d0;
            color: #166534;
        }

        .progress-step.current {
            background: #4f9b4f;
            color: #fff;
        }

        .history-list {
            display: grid;
            gap: 10px;
        }

        .history-list h5 {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .history-item {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 10px;
            align-items: center;
            background: #f8fafc;
            padding: 10px 12px;
            border-radius: 12px;
        }

        .history-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: #cbd5f5;
        }

        .history-dot.good { background: #22c55e; }
        .history-dot.calm { background: #86efac; }
        .history-dot.neutral { background: #cbd5e1; }

        .history-title {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .history-date {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .history-tag {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 999px;
            background: #e2e8f0;
            color: var(--muted);
        }

        .warning-card {
            background: #fff7ed;
            color: #92400e;
        }

        @media (max-width: 1200px) {
            .dashboard-topbar {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .dashboard-topbar > div:first-child {
                text-align: left;
            }

            .dashboard-topbar .topbar-actions {
                justify-self: auto;
                align-self: flex-start;
            }
            .hero-metrics {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .evaluation-content {
                grid-template-columns: 1fr;
            }

            .emotion-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .dashboard-sidebar {
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
        }
    </style>

    <script>
        const note = document.getElementById('evaluation-note');
        const counter = document.getElementById('note-counter');
        if (note && counter) {
            note.addEventListener('input', () => {
                const count = note.value.length;
                counter.textContent = count + '/300';
            });
        }
    </script>
</x-app-layout>
