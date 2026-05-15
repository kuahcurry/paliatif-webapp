@props([
    'active' => null,
    'brandLine' => null,
])

@php
    $navItems = [
        [
            'key' => 'dashboard',
            'label' => __('Beranda'),
            'route' => 'dashboard',
            'routeIs' => 'dashboard',
            'icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z M9 22V12h6v10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'key' => 'assessment',
            'label' => __('Pengkajian'),
            'route' => 'menu.assessment',
            'routeIs' => 'menu.assessment',
            'icon' => '<path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2 M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z M9 13l2 2 4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'key' => 'spiritual-needs',
            'label' => __('Intervensi'),
            'route' => 'menu.spiritual-needs',
            'routeIs' => 'menu.spiritual-needs',
            'icon' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'key' => 'evaluation',
            'label' => __('Evaluasi'),
            'route' => 'menu.emotional-evaluation',
            'routeIs' => 'menu.emotional-evaluation',
            'icon' => '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z M8 14s1.5 2 4 2 4-2 4-2 M9 9h.01M15 9h.01" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'key' => 'journals',
            'label' => __('Jurnal & Catatan'),
            'route' => 'journals.index',
            'routeIs' => 'journals.*',
            'icon' => '<path d="M4 6h16M4 10h16M4 14h12M4 18h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'key' => 'education',
            'label' => __('Modul Edukasi'),
            'route' => 'education.index',
            'routeIs' => 'education.*',
            'icon' => '<path d="M12 6l8 4-8 4-8-4 8-4z M4 10l8 4 8-4 M4 14l8 4 8-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
        [
            'key' => 'profile',
            'label' => __('Pengaturan'),
            'route' => 'profile.edit',
            'routeIs' => 'profile.edit',
            'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2 M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
        ],
    ];

    $adminItems = Auth::user()?->is_admin ? [
        ['key' => 'admin', 'label' => __('Dashboard'), 'route' => 'admin.dashboard', 'routeIs' => 'admin.dashboard', 'icon' => '<path d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['key' => 'admin-prayers', 'label' => __('Doa'), 'route' => 'admin.prayers.index', 'routeIs' => 'admin.prayers.*', 'icon' => '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['key' => 'admin-journals', 'label' => __('Jurnal'), 'route' => 'admin.journals.index', 'routeIs' => 'admin.journals.*', 'icon' => '<path d="M4 6h16M4 10h16M4 14h12M4 18h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['key' => 'admin-education', 'label' => __('Modul'), 'route' => 'admin.education.index', 'routeIs' => 'admin.education.*', 'icon' => '<path d="M12 6l8 4-8 4-8-4 8-4z M4 10l8 4 8-4 M4 14l8 4 8-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['key' => 'admin-evaluations', 'label' => __('Evaluasi'), 'route' => 'admin.evaluations.index', 'routeIs' => 'admin.evaluations.*', 'icon' => '<path d="M4 20h16M4 20V4m0 16h16M6 16V9m4 7v-5m4 5v-7m4 7v-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'],
    ] : [];
@endphp

<div class="sidebar-brand">
    <div class="brand-icon">
        <span></span>
    </div>
    <div>
        <h1>Terapi Rohani</h1>
        <p>Pasien Paliatif</p>
        @if ($brandLine)
            <small>{{ $brandLine }}</small>
        @endif
    </div>
</div>

<nav class="sidebar-nav">
    @if (request()->routeIs('admin.*'))
        @foreach ($adminItems as $item)
            @php $isActive = $active ? $active === $item['key'] : request()->routeIs($item['routeIs']); @endphp
            <a class="nav-item {{ $isActive ? 'is-active' : '' }}" href="{{ route($item['route']) }}">
                <span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true">{!! $item['icon'] !!}</svg></span>
                {{ $item['label'] }}
            </a>
        @endforeach
    @else
        @foreach ($navItems as $item)
            @php $isActive = $active ? $active === $item['key'] : request()->routeIs($item['routeIs']); @endphp
            <a class="nav-item {{ $isActive ? 'is-active' : '' }}" href="{{ route($item['route']) }}">
                <span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true">{!! $item['icon'] !!}</svg></span>
                {{ $item['label'] }}
            </a>
        @endforeach
        @if ($adminItems)
            <div class="sidebar-divider"></div>
            <div class="sidebar-section-label">Admin</div>
            @foreach ($adminItems as $item)
                @php $isActive = $active ? $active === $item['key'] : request()->routeIs($item['routeIs']); @endphp
                <a class="nav-item {{ $isActive ? 'is-active' : '' }}" href="{{ route($item['route']) }}">
                    <span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true">{!! $item['icon'] !!}</svg></span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        @endif
    @endif
</nav>

<style>
    .sidebar-divider {
        height: 1px;
        background: #edf2f7;
        margin: 12px 0 8px;
    }

    .sidebar-section-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        padding: 4px 12px 8px;
    }
</style>

