@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $emotionOptions = [
        ['value' => 'very_calm', 'label' => 'Sangat Tenang', 'desc' => 'Merasa sangat damai, penuh harapan dan bersyukur.', 'face' => 'face-happy'],
        ['value' => 'calm', 'label' => 'Tenang', 'desc' => 'Merasa lebih tenang dan nyaman dari sebelumnya.', 'face' => 'face-calm'],
        ['value' => 'neutral', 'label' => 'Biasa Saja', 'desc' => 'Belum ada perubahan yang signifikan dalam perasaan saya.', 'face' => 'face-neutral'],
        ['value' => 'anxious_sad', 'label' => 'Cemas / Sedih', 'desc' => 'Masih merasa cemas, sedih, atau khawatir dengan kondisi saya.', 'face' => 'face-anxious'],
        ['value' => 'distressed', 'label' => 'Sangat Tertekan', 'desc' => 'Merasa sangat tertekan, putus asa atau kehilangan harapan.', 'face' => 'face-distress'],
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

            @if (session('status') === 'evaluation-saved')
                <div class="alert success">Evaluasi berhasil disimpan. Terima kasih telah berbagi perasaan Anda.</div>
            @endif
            @if ($errors->any())
                <div class="alert warning">{{ $errors->first() }}</div>
            @endif
            @if ($hasReachedMax)
                <div class="alert warning">Anda telah menyelesaikan seluruh 5 sesi evaluasi. Terima kasih atas partisipasi Anda.</div>
            @endif

            <section class="card evaluation-hero">
                <div class="patient-profile">
                    <div class="patient-avatar">{{ strtoupper(substr($patientName, 0, 1)) }}</div>
                    <div>
                        <h3>{{ $patientName }}</h3>
                        <p>{{ $patientAge }}, {{ $patientGender }}</p>
                    </div>
                </div>
                <div class="hero-metrics">
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v3M16 2v3M3 7h18M5 5h14a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z M3 11h18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Sesi Layanan ke</p>
                            <p class="metric-value">{{ $sessionCount }} dari {{ $maxSessions }}</p>
                            <p class="metric-sub">Gunakan layanan hingga 5 kali</p>
                        </div>
                    </div>
                    <div class="metric">
                        <div class="metric-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div>
                            <p class="metric-label">Layanan Terakhir</p>
                            <p class="metric-value">{{ $lastService }}</p>
                            <p class="metric-sub">{{ $lastServiceDate }}</p>
                            <a class="metric-link" href="{{ route('menu.emotional-evaluation') }}">Lihat Detail</a>
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

                        @if (! $hasReachedMax)
                            <form method="POST" action="{{ route('menu.emotional-evaluation.store') }}">
                                @csrf

                                <div class="emotion-grid">
                                    @foreach ($emotionOptions as $emo)
                                        <label class="emotion-card {{ $lastEmotion === $emo['value'] ? 'selected' : '' }}" data-value="{{ $emo['value'] }}">
                                            <div class="face {{ $emo['face'] }}">
                                                <span class="eye left"></span>
                                                <span class="eye right"></span>
                                                <span class="mouth"></span>
                                            </div>
                                            <h5>{{ $emo['label'] }}</h5>
                                            <p>{{ $emo['desc'] }}</p>
                                            <input type="radio" name="emotion" value="{{ $emo['value'] }}" @checked($lastEmotion === $emo['value']) required hidden>
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error :messages="$errors->get('emotion')" class="mt-2" />

                                <div class="note-section">
                                    <label for="evaluation-note">Catatan Tambahan (Opsional)</label>
                                    <textarea id="evaluation-note" name="note" rows="3" maxlength="300" placeholder="Tuliskan perasaan atau hal yang ingin Anda sampaikan...">{{ $lastNote }}</textarea>
                                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                                    <div class="note-footer">
                                        <span id="note-counter">0/300</span>
                                        <button type="submit" class="primary-button">Simpan Evaluasi</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p class="empty-state">Anda telah menyelesaikan seluruh sesi evaluasi. Terima kasih.</p>
                        @endif
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
                                <div class="progress-step {{ $i < $currentSessionNumber ? 'done' : ($i === $currentSessionNumber && !$hasReachedMax ? 'current' : '') }}">
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
            width: 20px;
            height: 0;
            border: none;
            border-top: 3px solid #1f2937;
            border-radius: 0;
            top: 42px;
            left: 25px;
        }

        .face-anxious .mouth {
            width: 18px;
            height: 8px;
            border-color: transparent transparent #1f2937 transparent;
            border-radius: 0 0 999px 999px;
            border-width: 3px;
            top: 40px;
            left: 26px;
            transform: rotate(180deg);
        }

        .face-distress .mouth {
            width: 24px;
            height: 10px;
            border-color: transparent transparent #1f2937 transparent;
            border-radius: 0 0 999px 999px;
            border-width: 3px;
            top: 38px;
            left: 23px;
            transform: rotate(180deg);
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

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .alert.success {
            background: #ecfdf3;
            color: #15803d;
        }

        .alert.warning {
            background: #fff7ed;
            color: #92400e;
        }

        .empty-state {
            font-size: 0.85rem;
            color: var(--muted);
            text-align: center;
            padding: 24px 0;
        }

        .emotion-card {
            cursor: pointer;
        }

        .emotion-card input[type="radio"] {
            display: none;
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
                grid-template-columns: 1fr 1fr;
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
        document.querySelectorAll('.emotion-card').forEach(card => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.emotion-card').forEach(c => c.classList.remove('selected'));
                card.classList.add('selected');
                card.querySelector('input[type="radio"]').checked = true;
            });
        });

        const note = document.getElementById('evaluation-note');
        const counter = document.getElementById('note-counter');
        if (note && counter) {
            const update = () => { counter.textContent = note.value.length + '/300'; };
            note.addEventListener('input', update);
            update();
        }
    </script>
</x-app-layout>
