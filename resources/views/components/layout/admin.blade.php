<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - LMS</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body class="bg-gradient-dark text-white min-h-screen">
@php
    $pageTitleSection = trim($__env->yieldContent('page_title'));
    if ($pageTitleSection) {
        $pageTitle = $pageTitleSection;
    } else {
        if (request()->routeIs('admin.categories.*')) {
            $pageTitle = 'Kategori';
        } elseif (request()->routeIs('admin.tags.*')) {
            $pageTitle = 'Tag';
        } elseif (request()->routeIs('admin.mentor_verifications.*')) {
            $pageTitle = 'Verifikasi Mentor';
        } elseif (request()->routeIs('admin.transactions.*')) {
            $pageTitle = 'Transaksi';
        } elseif (request()->routeIs('admin.admin-bank-accounts.*')) {
            $pageTitle = 'Rekening Admin';
        } elseif (request()->routeIs('admin.announcements.*')) {
            $pageTitle = 'Pengumuman';
        } elseif (request()->routeIs('admin.settings.*')) {
            $pageTitle = 'Pengaturan';
        } elseif (request()->routeIs('admin.dashboard')) {
            $pageTitle = 'Dashboard';
        } else {
            $pageTitle = 'Dashboard';
        }
    }
@endphp
<div class="flex">
    <aside class="w-64 p-6 glass glass--deep min-h-screen">
        <x-layout.dashboard.sidebar title="EduLux LMS" subtitle="Student Dashboard" />
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