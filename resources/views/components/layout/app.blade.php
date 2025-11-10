<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduLux LMS</title>
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
  <body class="min-h-screen bg-gradient-to-br from-neutral-100 via-white to-neutral-200 text-gray-900
               dark:from-neutral-900 dark:via-black dark:to-neutral-800 dark:text-gray-100">
@include('components.layout.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      @yield('content')
    </main>
  </body>
</html>