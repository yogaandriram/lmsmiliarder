@props([
  'title' => 'EduLux LMS',
  'subtitle' => 'Mentor Dashboard',
])

@php
  $isDashboard = request()->routeIs('mentor.dashboard');
  $isCourses = request()->routeIs('mentor.courses.*');
  $isEbooks = request()->routeIs('mentor.ebooks.*');
  $isQuizzes = request()->routeIs('mentor.quizzes.*');
  $isDiscussions = request()->routeIs('mentor.discussions.*');
  $isProfile = request()->routeIs('mentor.profile.*');
  $isCommissions = request()->routeIs('mentor.commissions.*');
  $isSales = request()->routeIs('mentor.sales.*');
  $isAnalytics = request()->routeIs('mentor.analytics.*');
@endphp

<div class="space-y-6">
  <x-ui.sidebar.brand :title="$title" :subtitle="$subtitle" />

  <nav class="space-y-6">
    <x-ui.sidebar.section>
      <x-ui.sidebar.item href="{{ route('mentor.dashboard') }}" icon="fa-solid fa-gauge" label="Dashboard" :active="$isDashboard" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Kelola Konten">
      <x-ui.sidebar.item href="{{ route('mentor.courses.index') }}" icon="fa-solid fa-chalkboard-teacher" label="Kursus Saya" :active="$isCourses" />
      <x-ui.sidebar.item href="{{ route('mentor.ebooks.index') }}" icon="fa-solid fa-book-open" label="E-book Saya" :active="$isEbooks" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Interaksi">
      <x-ui.sidebar.item href="{{ route('mentor.discussions.index') }}" icon="fa-solid fa-comments" label="Diskusi" :active="$isDiscussions" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Laporan">
      <x-ui.sidebar.item href="{{ route('mentor.analytics.index') }}" icon="fa-solid fa-chart-line" label="Analitik" :active="$isAnalytics" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Penjualan">
      <x-ui.sidebar.item href="{{ route('mentor.sales.index') }}" icon="fa-solid fa-money-bill-wave" label="Penjualan" :active="$isSales" />
      <x-ui.sidebar.item href="{{ route('mentor.commissions.index') }}" icon="fa-solid fa-scale-balanced" label="Komisi" :active="$isCommissions" />
    </x-ui.sidebar.section>

    
  </nav>
</div>
