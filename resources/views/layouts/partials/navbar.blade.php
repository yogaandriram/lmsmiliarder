<header class="sticky top-0 z-20">
    <div class="max-w-6xl mx-auto px-4 py-3">
        <!-- Glassmorphism navbar wrapper -->
        <div class="flex items-center justify-between rounded-2xl shadow-xl backdrop-blur-md
                    bg-gradient-to-r from-yellow-400/25 via-white/60 to-neutral-200/70
                    dark:from-yellow-500/20 dark:via-neutral-900/60 dark:to-black/70
                    ring-1 ring-black/10 dark:ring-white/10 px-4 py-2">
            <!-- Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <i class="fa-solid fa-graduation-cap text-yellow-400 text-2xl"></i>
                <span class="font-semibold text-gray-900 dark:text-white tracking-wide group-hover:text-yellow-300">EduLux LMS</span>
            </a>

            <!-- Links (desktop) -->
            <nav class="hidden md:flex items-center gap-6 text-gray-700 dark:text-gray-200">
                <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
                <a href="#" class="hover:text-yellow-300 transition-colors">Kursus</a>
                <a href="#" class="hover:text-yellow-300 transition-colors">Tentang Kami</a>
                <!-- Theme toggle placeholder -->
                <button id="theme-toggle" type="button" aria-label="Toggle theme"
                        class="ml-2 text-yellow-300/80 hover:text-yellow-300 theme-toggle">
                    <i id="theme-toggle-icon" data-theme-icon class="fa-regular text-lg fa-sun"></i>
                </button>
            </nav>

            <!-- Theme toggle (mobile) -->
            <button type="button" aria-label="Toggle theme"
                    class="md:hidden text-yellow-300/80 hover:text-yellow-300 theme-toggle">
                <i data-theme-icon class="fa-regular text-lg fa-sun"></i>
            </button>

            <!-- Auth -->
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-yellow-400/50 px-3 py-1.5
                          text-yellow-300 hover:text-black hover:bg-yellow-400 transition-colors">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk</span>
                </a>
            </div>
        </div>
    </div>
</header>