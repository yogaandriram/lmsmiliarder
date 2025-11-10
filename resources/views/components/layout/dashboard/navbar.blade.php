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
            <div class="hidden md:block">
                <x-ui.navbar.admin.search />
            </div>

            <!-- Kanan: Notification, Pengaturan, Profile (Reusable buttons) -->
            <div class="flex items-center gap-3">
                <x-ui.navbar.admin.button icon="fa-regular fa-bell" variant="glass" />
                <x-ui.navbar.admin.button icon="fa-solid fa-gear" variant="glass" />
                <x-ui.navbar.admin.button icon="fa-solid fa-user" variant="glass" />
            </div>
        </div>
    </div>
</header>