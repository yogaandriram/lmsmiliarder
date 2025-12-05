<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduLux LMS - Autentikasi</title>
    <!-- Prevent FOUC - apply theme before page renders -->
    <script>
      (function(){
        try {
          var stored = localStorage.getItem('theme');
          if (stored === 'dark') {
            document.documentElement.classList.add('dark');
          } else if (stored === 'light') {
            document.documentElement.classList.remove('dark');
          } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
          }
        } catch (e) {}
      })();
    </script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-linear-to-br from-neutral-100 via-white to-neutral-200 text-gray-900
             dark:from-neutral-900 dark:via-black dark:to-neutral-800 dark:text-gray-100">
@include('components.layout.navbar')

    <main class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-10 overflow-hidden">
        <h1 class="text-center text-4xl font-semibold text-yellow-400 mb-6">EduLux LMS</h1>
        <div class="relative overflow-hidden">
            <div class="absolute -inset-24 rounded-[1.75rem] pointer-events-none">
                <div class="absolute inset-0 blur-[80px] opacity-100 bg-[radial-gradient(120%_80%_at_75%_70%,rgba(234,179,8,0.28),rgba(234,179,8,0.12),transparent)] dark:bg-[radial-gradient(120%_80%_at_75%_70%,rgba(234,179,8,0.22),rgba(234,179,8,0.1),transparent)]"></div>
                <div class="absolute inset-0 blur-[50px] bg-[radial-gradient(120%_80%_at_35%_25%,rgba(255,255,255,0.12),transparent)] dark:bg-[radial-gradient(120%_80%_at_35%_25%,rgba(255,255,255,0.06),transparent)]"></div>
                <div class="absolute inset-0 blur-[120px] opacity-70 mix-blend-screen animate-pulse bg-[radial-gradient(110%_70%_at_50%_55%,rgba(234,179,8,0.40),rgba(234,179,8,0.18),transparent)] dark:bg-[radial-gradient(110%_70%_at_50%_55%,rgba(234,179,8,0.34),rgba(234,179,8,0.16),transparent)]"></div>
            </div>
            <div class="relative z-10 rounded-2xl p-8 border ring-1 shadow-2xl
                        bg-white/10 backdrop-blur-2xl ring-white/10 border-white/10 shadow-black/30
                        dark:bg-white/5 dark:ring-white/10 dark:border-white/10">
                @yield('content')
            </div>
        </div>
    </main>
</body>
</html>
