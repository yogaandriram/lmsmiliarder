<header class="sticky top-0 z-20">
    <div class="max-w-6xl mx-auto px-4 py-3">
        <div class="flex items-center justify-between rounded-2xl shadow-xl backdrop-blur-md
                    bg-gradient-to-r from-yellow-400/25 via-white/60 to-neutral-200/70
                    dark:from-yellow-500/20 dark:via-neutral-900/60 dark:to-black/70
                    ring-1 ring-black/10 dark:ring-white/10 px-4 py-2">
            <!-- Brand -->
            <x-ui.navbar.brand />

            <!-- Links (desktop) + desktop theme toggle slot -->
            <x-ui.navbar.links>
                <x-ui.navbar.theme-toggle desktop="true" />
            </x-ui.navbar.links>

            <!-- Theme toggle (mobile) -->
            <x-ui.navbar.theme-toggle />

            <!-- Auth -->
            <x-ui.navbar.auth />
        </div>
    </div>
</header>