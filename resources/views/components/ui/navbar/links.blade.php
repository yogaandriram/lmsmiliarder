<nav class="hidden md:flex items-center gap-6 text-gray-700 dark:text-gray-200">
  <a href="{{ route('home') }}" class="hover:text-yellow-300 transition-colors">Beranda</a>
  <a href="{{ route('public.courses.index') }}" class="hover:text-yellow-300 transition-colors">Kursus</a>
  <a href="{{ route('about') }}" class="hover:text-yellow-300 transition-colors">Tentang Kami</a>
  {{ $slot }}
</nav>