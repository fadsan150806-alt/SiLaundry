@php
    $user = auth()->user();
    $dashboardRoute = $user->dashboardRoute();
    $activePengaturan = request()->routeIs('users.*');
@endphp

<nav class="flex-1 space-y-0.5 overflow-y-auto px-3 pb-4 text-sm">
    @if ($dashboardRoute)
        <x-sidebar-link :href="route($dashboardRoute)" :active="request()->routeIs('dashboard.*')" icon="layout-dashboard">
            Dashboard
        </x-sidebar-link>
    @endif

    @if ($user->isAdmin())
        <x-sidebar-group label="Pengaturan" icon="settings" :active="$activePengaturan">
            <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')" icon="user-round">
                Pengguna
            </x-sidebar-link>
        </x-sidebar-group>
    @endif
</nav>
