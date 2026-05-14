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
            'icon' => '<path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" fill="currentColor"/>',
        ],
        [
            'key' => 'assessment',
            'label' => __('Pengkajian'),
            'route' => 'menu.assessment',
            'routeIs' => 'menu.assessment',
            'icon' => '<path d="M6 3h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm8 1v4h4" fill="currentColor"/>',
        ],
        [
            'key' => 'spiritual-needs',
            'label' => __('Intervensi'),
            'route' => 'menu.spiritual-needs',
            'routeIs' => 'menu.spiritual-needs',
            'icon' => '<path d="M12 3l2.2 5.4 5.8.5-4.4 3.8 1.4 5.7L12 15.8 7 18.4l1.4-5.7L4 8.9l5.8-.5z" fill="currentColor"/>',
        ],
        [
            'key' => 'evaluation',
            'label' => __('Evaluasi'),
            'route' => 'menu.emotional-evaluation',
            'routeIs' => 'menu.emotional-evaluation',
            'icon' => '<path d="M4 5h16a1 1 0 0 1 1 1v12l-4-3H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" fill="currentColor"/>',
        ],
        [
            'key' => 'journals',
            'label' => __('Jurnal & Catatan'),
            'route' => 'journals.index',
            'routeIs' => 'journals.*',
            'icon' => '<path d="M7 2v3M17 2v3M4 7h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7zm2 4h4v4H6z" fill="currentColor"/>',
        ],
        [
            'key' => 'education',
            'label' => __('Modul Edukasi'),
            'route' => 'education.index',
            'routeIs' => 'education.*',
            'icon' => '<path d="M3 6l9-4 9 4-9 4-9-4zm0 5l9 4 9-4v7l-9 4-9-4v-7z" fill="currentColor"/>',
        ],
        [
            'key' => 'profile',
            'label' => __('Pengaturan'),
            'route' => 'profile.edit',
            'routeIs' => 'profile.edit',
            'icon' => '<path d="M12 8a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm8 8.5V22H4v-5.5A6.5 6.5 0 0 1 10.5 10h3A6.5 6.5 0 0 1 20 16.5z" fill="currentColor"/>',
        ],
    ];
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
    @foreach ($navItems as $item)
        @php
            $isActive = $active
                ? $active === $item['key']
                : request()->routeIs($item['routeIs']);
        @endphp
        <a class="nav-item {{ $isActive ? 'is-active' : '' }}" href="{{ route($item['route']) }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">{!! $item['icon'] !!}</svg>
            </span>
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>

