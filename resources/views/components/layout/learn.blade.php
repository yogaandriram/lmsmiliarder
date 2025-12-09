<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belajar - LMS</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    @stack('styles')
</head>
<body class="bg-gradient-dark text-white min-h-screen">
@php
    $pageTitle = trim($__env->yieldContent('page_title')) ?: 'Belajar';
@endphp
<div class="flex">
    <aside class="w-64 p-6 glass glass--deep sticky top-0 h-screen overflow-y-auto">
        <x-layout.learn-sidebar :course="$course ?? null" :current="$current ?? null" />
    </aside>
    <div class="flex-1 flex flex-col">
        <div class="px-8 pt-6">
            <div class="text-xl font-semibold">{{ $pageTitle }}</div>
        </div>
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
