@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

@php
    $faqItems = [
        ['q' => 'Apa itu Terapi Rohani Pasien Paliatif?', 'a' => 'Layanan pendampingan spiritual dan psikososial untuk pasien paliatif dan caregiver, membantu menemukan makna, ketenangan, dan dukungan emosional.'],
        ['q' => 'Bagaimana cara menggunakan modul edukasi?', 'a' => 'Anda dapat mengakses modul edukasi melalui menu <strong>Modul Edukasi</strong> di sidebar. Pilih modul yang sesuai dengan kebutuhan Anda, baik berupa artikel maupun video.'],
        ['q' => 'Apa yang harus saya lakukan jika merasa cemas atau tertekan?', 'a' => 'Gunakan fitur <strong>Evaluasi Perasaan</strong> untuk melaporkan kondisi emosional Anda. Tim pendamping akan memantau dan memberikan dukungan yang diperlukan.'],
        ['q' => 'Bagaimana cara mengisi cek harian?', 'a' => 'Cek harian dapat diisi melalui halaman <strong>Dashboard Spiritual</strong>. Isi skala spiritual dan gejala Anda setiap hari untuk memantau perkembangan.'],
        ['q' => 'Apa itu Pohon Doa?', 'a' => 'Pohon Doa adalah fitur berbagi doa dan dukungan spiritual antar pengguna. Anda dapat mengirimkan doa dan memberikan dukungan kepada doa pengguna lain.'],
        ['q' => 'Bagaimana cara menghubungi tenaga profesional?', 'a' => 'Jika Anda membutuhkan bantuan lebih lanjut, silakan hubungi tim pendamping melalui informasi kontak yang tersedia di halaman <strong>Profil</strong> atau melalui menu Bantuan.'],
    ];
@endphp

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen faq-page">
    <div class="faq-layout">
        <aside class="faq-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="faq-main">
            <x-app-topbar
                class="faq-topbar"
                title="Pusat Bantuan"
                subtitle="Pertanyaan yang sering diajukan"
                :showMenuButton="true"
                :badgeCount="0"
            />

            <div class="faq-content">
                <div class="card">
                    <div class="section-header">
                        <div>
                            <h4>Pertanyaan Umum</h4>
                            <p>Temukan jawaban atas pertanyaan seputar penggunaan layanan Terapi Rohani.</p>
                        </div>
                    </div>

                    <div class="faq-list">
                        @foreach ($faqItems as $item)
                            <details class="faq-item">
                                <summary>
                                    <span>{{ $item['q'] }}</span>
                                    <svg class="faq-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                                </summary>
                                <p>{!! $item['a'] !!}</p>
                            </details>
                        @endforeach
                    </div>
                </div>

                <div class="card help-card">
                    <div>
                        <h4>Masih butuh bantuan?</h4>
                        <p>Jika pertanyaan Anda belum terjawab, jangan ragu untuk menghubungi tim pendamping kami.</p>
                    </div>
                    <a href="{{ route('faq') }}" class="ghost-button">Hubungi Kami</a>
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

        .faq-page {
            background: #f4f6fb;
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
        }

        .faq-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        .faq-sidebar {
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

        .faq-main {
            padding: 26px 32px 48px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .faq-topbar {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
        }

        .faq-topbar > div:not(.topbar-actions) {
            grid-column: 2;
            text-align: center;
        }

        .faq-topbar .ghost-button {
            grid-column: 1;
            justify-self: start;
        }

        .faq-topbar .topbar-actions {
            grid-column: 3;
            justify-self: end;
        }

        .faq-topbar h2 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .faq-topbar p {
            color: var(--muted);
            font-size: 0.85rem;
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
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
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
            border: none;
            cursor: pointer;
            font-family: inherit;
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
            font-size: 0.85rem;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            text-align: left;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--muted);
            text-align: left;
        }

        .chevron {
            color: var(--muted);
        }

        .faq-content {
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
        }

        .section-header h4 {
            font-weight: 700;
        }

        .section-header p {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .faq-list {
            display: grid;
            gap: 8px;
        }

        .faq-item {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
            background: #f8fafc;
        }

        .faq-item summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1f2937;
            cursor: pointer;
            list-style: none;
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-chevron {
            flex-shrink: 0;
            color: var(--muted);
            transition: transform 0.2s ease;
        }

        .faq-item[open] .faq-chevron {
            transform: rotate(180deg);
        }

        .faq-item p {
            padding: 0 16px 14px;
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .help-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .help-card h4 {
            font-weight: 700;
        }

        .help-card p {
            font-size: 0.78rem;
            color: var(--muted);
        }

        .ghost-button {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            border-radius: 12px;
            padding: 8px 14px;
            font-size: 0.82rem;
            cursor: pointer;
            white-space: nowrap;
        }

        @media (max-width: 900px) {
            .faq-layout {
                grid-template-columns: 1fr;
            }

            .faq-sidebar {
                position: sticky;
                top: 0;
                z-index: 10;
                flex-direction: row;
                overflow-x: auto;
                gap: 12px;
            }

            .faq-topbar {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .faq-topbar > div:not(.topbar-actions) {
                text-align: left;
            }

            .faq-topbar .topbar-actions {
                justify-self: auto;
                align-self: flex-start;
            }

            .help-card {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</x-app-layout>
