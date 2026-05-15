@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $categoryLabels = [
        'self' => 'Untuk diri sendiri',
        'others' => 'Untuk orang lain',
        'gratitude' => 'Doa syukur',
    ];
    $positions = [
        ['x' => 18, 'y' => 12], ['x' => 35, 'y' => 8], ['x' => 52, 'y' => 14], ['x' => 70, 'y' => 10],
        ['x' => 25, 'y' => 30], ['x' => 45, 'y' => 28], ['x' => 62, 'y' => 30], ['x' => 78, 'y' => 26],
        ['x' => 15, 'y' => 48], ['x' => 32, 'y' => 46], ['x' => 50, 'y' => 44], ['x' => 68, 'y' => 46],
        ['x' => 84, 'y' => 42], ['x' => 22, 'y' => 64], ['x' => 40, 'y' => 62], ['x' => 58, 'y' => 62],
        ['x' => 76, 'y' => 60], ['x' => 90, 'y' => 58],
    ];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen prayer-page">
    <div class="prayer-layout">
        <aside class="prayer-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="prayer-main">
            <x-app-topbar
                class="prayer-topbar"
                title="Pohon Doa"
                subtitle="Bagikan doa dan dukungan"
                :showMenuButton="true"
            />

            @if (session('status') === 'prayer-public')
                <div class="alert success">Doa Anda tersimpan dan tampil di pohon.</div>
            @endif
            @if (session('status') === 'prayer-private')
                <div class="alert info">Doa Anda tersimpan sebagai privat.</div>
            @endif
            @if (session('status') === 'support-added')
                <div class="alert success">Terima kasih sudah ikut mendoakan.</div>
            @endif
            @if ($errors->has('recaptcha'))
                <div class="alert warning">{{ $errors->first('recaptcha') }}</div>
            @endif

            <div class="prayer-content">
                <div class="card">
                    <div class="section-header">
                        <div>
                            <h4>Titip Doa</h4>
                            <p>Tulis doa singkat. Anda bisa menggunakan nama atau anonim.</p>
                        </div>
                        <form method="GET" action="{{ route('prayers.index') }}" class="filter-inline">
                            <select name="category" onchange="this.form.submit()">
                                <option value="all" @selected($category === 'all')>Semua</option>
                                <option value="self" @selected($category === 'self')>Untuk diri sendiri</option>
                                <option value="others" @selected($category === 'others')>Untuk orang lain</option>
                                <option value="gratitude" @selected($category === 'gratitude')>Doa syukur</option>
                            </select>
                            <select name="sort" onchange="this.form.submit()">
                                <option value="latest" @selected($sort === 'latest')>Terbaru</option>
                                <option value="support" @selected($sort === 'support')>Paling didoakan</option>
                            </select>
                        </form>
                    </div>

                    <div class="rules-box">
                        <p class="rules-title">Aturan singkat</p>
                        <ul>
                            <li>Gunakan bahasa yang sopan dan tidak menyinggung pihak lain.</li>
                            <li>Hindari informasi pribadi yang sensitif.</li>
                            <li>Doa yang melanggar dapat dihapus oleh admin.</li>
                        </ul>
                    </div>

                    <form id="prayer-form" method="POST" action="{{ route('prayers.store') }}" class="prayer-form">
                        @csrf

                        <div class="form-row">
                            <label for="display_name">Nama/alias (opsional)</label>
                            <input id="display_name" name="display_name" type="text" value="{{ old('display_name') }}" placeholder="Kosongkan untuk anonim" />
                            <x-input-error :messages="$errors->get('display_name')" />
                        </div>

                        <div class="form-row">
                            <label for="category">Kategori doa</label>
                            <select id="category" name="category">
                                <option value="">{{ __('Pilih kategori') }}</option>
                                <option value="self" @selected(old('category') === 'self')>Untuk diri sendiri</option>
                                <option value="others" @selected(old('category') === 'others')>Untuk orang lain</option>
                                <option value="gratitude" @selected(old('category') === 'gratitude')>Doa syukur</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" />
                        </div>

                        <div class="form-row">
                            <label for="content">Isi doa (maksimal 500 karakter)</label>
                            <textarea id="content" name="content" rows="3" maxlength="500">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" />
                        </div>

                        <div class="form-row">
                            <label>Tampilkan doa</label>
                            <div class="radio-inline">
                                <label><input type="radio" name="is_public" value="1" @checked(old('is_public', '1') === '1')> <span>Publik di pohon doa</span></label>
                                <label><input type="radio" name="is_public" value="0" @checked(old('is_public') === '0')> <span>Privat (hanya tersimpan)</span></label>
                            </div>
                            <x-input-error :messages="$errors->get('is_public')" />
                        </div>

                        @if ($recaptchaSiteKey)
                            <input type="hidden" id="recaptcha_token" name="recaptcha_token" value="">
                        @endif

                        <button type="submit" class="primary-button">Kirim doa</button>
                    </form>
                </div>

                <div class="card">
                    <div class="section-header">
                        <div>
                            <h4>Daun Doa</h4>
                            <p>Klik salah satu daun untuk membaca doa dan ikut mendoakan.</p>
                        </div>
                    </div>

                    <div class="prayer-tree">
                        <div class="tree-trunk"></div>
                        <div class="tree-canopy"></div>
                        <div class="tree-leaves">
                            @forelse ($prayers as $index => $prayer)
                                @php
                                    $position = $positions[$index % count($positions)];
                                    $displayName = $prayer->display_name ?: 'Anonim';
                                    $categoryLabel = $categoryLabels[$prayer->category] ?? 'Doa';
                                @endphp
                                <div class="leaf" style="--leaf-x: {{ $position['x'] }}%; --leaf-y: {{ $position['y'] }}%;">
                                    <details class="leaf-card">
                                        <summary>
                                            <div class="leaf-title">{{ $displayName }}</div>
                                            <div class="leaf-subtitle">{{ $categoryLabel }}</div>
                                        </summary>
                                        <div class="leaf-content">
                                            <p>{{ $prayer->content }}</p>
                                            <div class="leaf-actions">
                                                <span>{{ $prayer->support_count }} orang ikut mendoakan</span>
                                                <form method="POST" action="{{ route('prayers.support', $prayer) }}">
                                                    @csrf
                                                    <button type="submit" class="ghost-button">Ikut mendoakan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </details>
                                </div>
                            @empty
                                <p class="empty-state">Belum ada doa publik. Jadilah yang pertama menitipkan doa.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
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
            --shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .prayer-page {
            background: #f4f6fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .prayer-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .prayer-sidebar {
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
            width: 42px; height: 42px;
            border-radius: 16px;
            background: #dff3df;
            display: grid;
            place-items: center;
        }

        .brand-icon span {
            width: 22px; height: 22px;
            border-radius: 999px;
            background: #63b96b;
            display: block;
        }

        .sidebar-brand h1 { font-size: 0.95rem; color: var(--text); }
        .sidebar-brand p { font-size: 0.8rem; color: var(--muted); }
        .sidebar-brand small { font-size: 0.7rem; color: #94a3b8; }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
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
            width: 20px; height: 20px;
            display: grid;
            place-items: center;
            color: currentColor;
        }

        .nav-icon svg { width: 18px; height: 18px; }

        .nav-item.is-active,
        .nav-item:hover {
            background: #e1f1e1;
            color: #256c32;
        }

        .nav-group { display: grid; gap: 6px; }
        .nav-sub { display: grid; gap: 6px; margin-left: 12px; }
        .nav-sub-item { font-size: 0.82rem; color: var(--muted); text-decoration: none; }
        .nav-sub-item.is-active { color: #256c32; font-weight: 600; }
        .sidebar-footer { margin-top: auto; }
        .footer-card { background: #f8fafc; border-radius: 16px; padding: 16px; font-size: 0.75rem; color: var(--muted); }
        .footer-title { font-weight: 700; color: var(--text); }
        .footer-subtitle { margin-bottom: 6px; }

        .prayer-main {
            padding: 26px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .prayer-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .prayer-topbar > div:not(.topbar-actions) { grid-column: 2; text-align: center; }
        .prayer-topbar .ghost-button { grid-column: 1; justify-self: start; }
        .prayer-topbar .topbar-actions { grid-column: 3; justify-self: end; }
        .prayer-topbar h2 { font-size: 1.4rem; font-weight: 700; }
        .prayer-topbar p { color: var(--muted); font-size: 0.85rem; }

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
            width: 38px; height: 38px;
            cursor: pointer;
            display: grid;
            place-items: center;
            color: #475569;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
        }

        .icon-button svg { width: 20px; height: 20px; }

        .badge {
            position: absolute;
            top: -4px; right: -4px;
            background: #22c55e;
            color: #ffffff;
            font-size: 0.65rem;
            width: 18px; height: 18px;
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
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .avatar {
            width: 34px; height: 34px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #1d4ed8;
            font-weight: 700;
            display: grid;
            place-items: center;
            font-size: 0.85rem;
        }

        .user-name { font-size: 0.85rem; font-weight: 600; color: var(--text); text-align: left; }
        .user-role { font-size: 0.75rem; color: var(--muted); text-align: left; }
        .chevron { color: var(--muted); }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .alert.success { background: #ecfdf3; color: #15803d; }
        .alert.info { background: #eff6ff; color: #1d4ed8; }
        .alert.warning { background: #fff7ed; color: #92400e; }

        .prayer-content {
            display: grid;
            gap: 8px;
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

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .section-header h4 { font-weight: 700; }
        .section-header p { font-size: 0.78rem; color: var(--muted); }

        .filter-inline {
            display: flex;
            gap: 8px;
        }

        .filter-inline select {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 6px 10px;
            font-size: 0.78rem;
        }

        .rules-box {
            background: #f8fafc;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 0.78rem;
            color: var(--muted);
        }

        .rules-title {
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .rules-box ul {
            list-style: disc;
            padding-left: 18px;
            display: grid;
            gap: 4px;
        }

        .prayer-form {
            display: grid;
            gap: 14px;
        }

        .form-row {
            display: grid;
            gap: 6px;
        }

        .form-row label {
            font-size: 0.82rem;
            color: var(--muted);
        }

        .form-row input[type="text"],
        .form-row select,
        .form-row textarea {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 12px;
            font-size: 0.85rem;
            width: 100%;
        }

        .radio-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .radio-inline label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
        }

        .primary-button {
            background: #4f9b4f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.82rem;
            width: fit-content;
            cursor: pointer;
        }

        .ghost-button {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 0.78rem;
            cursor: pointer;
            text-decoration: none;
        }

        .empty-state {
            font-size: 0.85rem;
            color: var(--muted);
            padding: 20px;
            text-align: center;
        }

        .prayer-tree {
            position: relative;
            min-height: 420px;
            background: radial-gradient(circle at 50% 25%, #d9f99d 0%, #f0fdf4 40%, #f8fafc 100%);
            border-radius: 24px;
            overflow: hidden;
        }

        .tree-trunk {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 90px;
            height: 180px;
            background: linear-gradient(180deg, #8b5e3c 0%, #6b4b32 100%);
            border-radius: 20px 20px 8px 8px;
        }

        .tree-canopy {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 260px;
            background: radial-gradient(circle at 50% 40%, #bbf7d0 0%, #86efac 45%, #4ade80 100%);
            border-radius: 999px;
            opacity: 0.35;
        }

        .tree-leaves {
            position: absolute;
            inset: 0;
        }

        .leaf {
            position: absolute;
            left: var(--leaf-x);
            top: var(--leaf-y);
            transform: translate(-50%, -50%);
        }

        .leaf-card {
            width: 160px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.3);
            border-radius: 16px;
            padding: 12px 14px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        }

        .leaf-card summary {
            list-style: none;
            cursor: pointer;
        }

        .leaf-card summary::-webkit-details-marker { display: none; }

        .leaf-title { font-size: 0.82rem; font-weight: 600; color: #0f172a; }
        .leaf-subtitle { margin-top: 2px; font-size: 0.72rem; color: #475569; }

        .leaf-content {
            margin-top: 10px;
            display: grid;
            gap: 10px;
        }

        .leaf-content p { font-size: 0.8rem; color: var(--text); }

        .leaf-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.7rem;
            color: var(--muted);
            flex-wrap: wrap;
            gap: 8px;
        }

        @media (max-width: 900px) {
            .prayer-layout { grid-template-columns: 1fr; }

            .prayer-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                flex-direction: row;
                overflow-x: auto;
                gap: 12px;
            }

            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }

            .prayer-topbar {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .prayer-topbar > div:not(.topbar-actions) { text-align: left; }
            .prayer-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }

            .prayer-tree { min-height: 520px; }

            .leaf {
                position: static;
                transform: none;
                margin-top: 12px;
            }

            .tree-trunk, .tree-canopy { display: none; }

            .tree-leaves {
                position: static;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
                gap: 12px;
                padding: 12px 0 24px;
            }
        }
    </style>

    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
        <script>
            const prayerForm = document.getElementById('prayer-form');
            const recaptchaField = document.getElementById('recaptcha_token');
            if (prayerForm && recaptchaField) {
                prayerForm.addEventListener('submit', (event) => {
                    if (recaptchaField.value) return;
                    event.preventDefault();
                    grecaptcha.ready(() => {
                        grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'prayer' }).then((token) => {
                            recaptchaField.value = token;
                            prayerForm.submit();
                        });
                    });
                });
            }
        </script>
    @endif
</x-app-layout>
