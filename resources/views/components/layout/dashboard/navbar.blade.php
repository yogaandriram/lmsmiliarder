@props([
  'title' => 'Dashboard'
])
<header class="sticky top-0 z-30">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between rounded-2xl shadow-xl glass--deep ring-1 ring-black/10 dark:ring-white/10 px-4 py-2">
            <!-- Kiri: Judul halaman -->
            <div class="flex items-center gap-2 font-semibold text-white">
                <i class="fa-solid fa-gauge text-white/80"></i>
                <span>{{ $title }}</span>
            </div>

            <!-- Tengah: (opsional) pencarian -->
            <div class="hidden md:block"></div>

            <!-- Kanan: Notification, Pengaturan, Profile, Logout -->
            <div class="flex items-center gap-3">
                @php $isAdmin = request()->routeIs('admin.*'); @endphp
                <x-ui.navbar.button icon="fa-regular fa-bell" variant="glass" href="{{ $isAdmin ? route('admin.announcements.index') : route('mentor.notifications') }}" />
                <x-ui.navbar.button icon="fa-solid fa-gear" variant="glass" href="{{ $isAdmin ? route('admin.settings.index') : route('mentor.settings') }}" />
                <x-ui.navbar.button icon="fa-solid fa-user" variant="glass" href="{{ $isAdmin ? '#' : route('mentor.profile') }}" />
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="h-10 w-10 flex items-center justify-center rounded-xl glass glass-hover ring-1 ring-yellow-300/25" title="Logout">
                        <i class="fa-solid fa-right-from-bracket text-white"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
