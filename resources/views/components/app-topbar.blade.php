@props([
    'title' => '',
    'subtitle' => null,
    'showMenuButton' => false,
    'badgeCount' => 3,
])

@php
    $user = Auth::user();
    $resolvedUserName = $user->name ?? 'Pengguna';
    $resolvedUserRole = $user && $user->is_admin ? 'Admin' : 'Pasien';
    $avatarLetter = strtoupper(substr($resolvedUserName, 0, 1));
    $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
    $recentNotifications = $user ? $user->notifications()->orderByDesc('created_at')->take(5)->get() : collect();
@endphp

<header {{ $attributes->merge(['class' => '', 'x-data' => '{ notifOpen: false, profileOpen: false }']) }}>
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
        <div class="notif-wrapper" @click.outside="notifOpen = false">
            <button class="icon-button" type="button" aria-label="Notifikasi" @click="notifOpen = ! notifOpen">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22a2 2 0 0 0 2-2H10a2 2 0 0 0 2 2zm7-6V11a7 7 0 1 0-14 0v5l-2 2v1h18v-1z" fill="currentColor"/></svg>
                @if ($unreadCount > 0)
                    <span class="badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                @endif
            </button>

            <div class="notif-dropdown" x-show="notifOpen" x-cloak>
                <div class="notif-header">
                    <h5>Notifikasi</h5>
                    @if ($unreadCount > 0)
                        <form method="POST" action="{{ route('notifications.markAllRead') }}">
                            @csrf
                            <button type="submit" class="notif-mark-read">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
                <div class="notif-list">
                    @forelse ($recentNotifications as $notif)
                        <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}">
                            <div class="notif-content">
                                <span class="notif-title">{{ $notif->title }}</span>
                                @if ($notif->body)
                                    <span class="notif-body">{{ Str::limit($notif->body, 80) }}</span>
                                @endif
                                <span class="notif-time">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            @if (! $notif->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notif) }}">
                                    @csrf
                                    <button type="submit" class="notif-dot" title="Tandai dibaca"></button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="notif-empty">Tidak ada notifikasi</div>
                    @endforelse
                </div>
                @if ($recentNotifications->isNotEmpty())
                    <a href="{{ route('notifications.index') }}" class="notif-footer">Lihat Semua Notifikasi</a>
                @endif
            </div>
        </div>

        <a href="{{ route('faq') }}" class="icon-button" type="button" aria-label="Bantuan">?</a>

        <div class="profile-wrapper" @click.outside="profileOpen = false">
            <button class="user-chip" type="button" @click="profileOpen = ! profileOpen">
                <div class="avatar">{{ $avatarLetter }}</div>
                <div>
                    <div class="user-name">{{ $resolvedUserName }}</div>
                    <div class="user-role">{{ $resolvedUserRole }}</div>
                </div>
                <svg class="chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>

            <div class="profile-dropdown" x-show="profileOpen" x-cloak>
                <a href="{{ route('profile.edit') }}" class="profile-dropdown-item">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="profile-dropdown-item">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    .icon-button svg {
        width: 20px;
        height: 20px;
    }

    .notif-wrapper, .profile-wrapper {
        position: relative;
    }

    .notif-dropdown, .profile-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        z-index: 50;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
        min-width: 320px;
        overflow: hidden;
    }

    .profile-dropdown {
        min-width: 180px;
    }

    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .notif-header h5 {
        font-size: 0.9rem;
        font-weight: 700;
    }

    .notif-mark-read {
        font-size: 0.7rem;
        color: #6366f1;
        background: none;
        border: none;
        cursor: pointer;
    }

    .notif-list {
        max-height: 320px;
        overflow-y: auto;
    }

    .notif-item {
        display: flex;
        gap: 10px;
        padding: 12px 16px;
        border-bottom: 1px solid #f8fafc;
        align-items: flex-start;
    }

    .notif-item.unread {
        background: #f8faff;
    }

    .notif-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .notif-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1f2937;
    }

    .notif-body {
        font-size: 0.72rem;
        color: #64748b;
    }

    .notif-time {
        font-size: 0.65rem;
        color: #94a3b8;
    }

    .notif-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #6366f1;
        border: none;
        cursor: pointer;
        margin-top: 6px;
        flex-shrink: 0;
    }

    .notif-empty {
        padding: 24px 16px;
        text-align: center;
        font-size: 0.8rem;
        color: #94a3b8;
    }

    .notif-footer {
        display: block;
        text-align: center;
        padding: 10px 16px;
        font-size: 0.78rem;
        color: #6366f1;
        border-top: 1px solid #f1f5f9;
        text-decoration: none;
    }

    .profile-dropdown-item {
        display: block;
        padding: 10px 16px;
        font-size: 0.82rem;
        color: #1f2937;
        text-decoration: none;
        background: none;
        border: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .profile-dropdown-item:hover {
        background: #f8fafc;
    }
</style>
