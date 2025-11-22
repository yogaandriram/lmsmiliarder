<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor - LMS</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body class="bg-gradient-dark text-white min-h-screen">
@php
    $pageTitleSection = trim($__env->yieldContent('page_title'));
    if ($pageTitleSection) {
        $pageTitle = $pageTitleSection;
    } else {
        if (request()->routeIs('mentor.courses.*')) {
            $pageTitle = 'Kursus Saya';
        } elseif (request()->routeIs('mentor.ebooks.*')) {
            $pageTitle = 'E-book Saya';
        } elseif (request()->routeIs('mentor.quizzes.*')) {
            $pageTitle = 'Kuis Saya';
        } elseif (request()->routeIs('mentor.discussions.*')) {
            $pageTitle = 'Diskusi';
        } elseif (request()->routeIs('mentor.profile.*')) {
            $pageTitle = 'Profil Mentor';
        } elseif (request()->routeIs('mentor.dashboard')) {
            $pageTitle = 'Dashboard Mentor';
        } else {
            $pageTitle = 'Dashboard Mentor';
        }
    }
@endphp
<div class="flex">
    <aside class="w-64 p-6 glass glass--deep sticky top-0 h-screen overflow-y-auto">
        <x-layout.dashboard.mentor-sidebar title="EduLux LMS" subtitle="Mentor Dashboard" />
    </aside>
    <div class="flex-1 flex flex-col">
        <x-layout.dashboard.navbar :title="$pageTitle" />
        <main class="flex-1 p-8">
            @if(session('success'))
                <div class="glass glass-hover p-4 mb-4 text-yellow-300">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>