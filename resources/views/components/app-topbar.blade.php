@props([
    'title' => '',
    'subtitle' => null,
    'showMenuButton' => false,
    'userName' => null,
    'userRole' => 'Perawat',
    'badgeCount' => 3,
])

@php
    $resolvedUserName = $userName ?? (Auth::user()->name ?? 'Siti Rahmawati');
    $resolvedUserRole = $userRole ?? 'Perawat';
    $avatarLetter = strtoupper(substr($resolvedUserName, 0, 1));
@endphp

<header {{ $attributes->merge(['class' => '']) }}>
    @if ($showMenuButton)
        <button class="ghost-button" type="button" aria-label="Menu">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
    @endif
    <div>
        <h2>{{ $title }}</h2>
        @if ($subtitle)
            <p>{{ $subtitle }}</p>
        @endif
    </div>
    <div class="topbar-actions">
        <button class="icon-button" type="button" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2zm7-6V11a7 7 0 1 0-14 0v5l-2 2v1h18v-1z" fill="currentColor"/></svg>
            @if ($badgeCount > 0)
                <span class="badge">{{ $badgeCount }}</span>
            @endif
        </button>
        <button class="icon-button" type="button" aria-label="Bantuan">?</button>
        <div class="user-chip">
            <div class="avatar">{{ $avatarLetter }}</div>
            <div>
                <div class="user-name">{{ $resolvedUserName }}</div>
                <div class="user-role">{{ $resolvedUserRole }}</div>
            </div>
            <span class="chevron">v</span>
        </div>
    </div>
</header>
