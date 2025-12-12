<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduLux LMS</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>
  <body class="bg-gradient-dark text-white min-h-screen">
@include('components.layout.navbar')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      @yield('content')
    </main>
  </body>
</html>
