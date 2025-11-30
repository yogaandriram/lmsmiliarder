@props([
  'title' => 'EduLux LMS',
  'subtitle' => 'Student Dashboard',
])

@php
  $isDashboard = request()->routeIs('admin.dashboard');
  $isCategories = request()->routeIs('admin.categories.*');
  $isTags = request()->routeIs('admin.tags.*');
  $isUsers = request()->routeIs('admin.users.*');
  $isMentors = request()->routeIs('admin.mentors.*');
  $isMentorVerify = request()->routeIs('admin.mentor_verifications.*');
  $isCourseVerify = request()->routeIs('admin.course_verifications.*');
  $isEbookVerify = request()->routeIs('admin.ebook_verifications.*');
  $isTransactions = request()->routeIs('admin.transactions.*');
@endphp

<div class="space-y-6">
  <x-ui.sidebar.brand :title="$title" :subtitle="$subtitle" />

  <nav class="space-y-6">
    <x-ui.sidebar.section>
      <x-ui.sidebar.item href="{{ route('admin.dashboard') }}" icon="fa-solid fa-gauge" label="Dashboard" :active="$isDashboard" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Manage Content">
      <x-ui.sidebar.item href="{{ route('admin.categories.index') }}" icon="fa-solid fa-folder" label="Kategori" :active="$isCategories" />
      <x-ui.sidebar.item href="{{ route('admin.tags.index') }}" icon="fa-solid fa-tags" label="Tags" :active="$isTags" />
      <x-ui.sidebar.item href="#" icon="fa-solid fa-chart-simple" label="Level" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Content Verification">
      <x-ui.sidebar.item href="{{ route('admin.course_verifications.index') }}" icon="fa-solid fa-book-open" label="Kursus" :active="$isCourseVerify" />
      <x-ui.sidebar.item href="{{ route('admin.ebook_verifications.index') }}" icon="fa-solid fa-book" label="E-Book" :active="$isEbookVerify" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Manage Account">
      <x-ui.sidebar.item href="{{ route('admin.users.index') }}" icon="fa-solid fa-user-group" label="Kelola User" :active="$isUsers" />
      <x-ui.sidebar.item href="{{ route('admin.mentors.index') }}" icon="fa-solid fa-user-tie" label="Kelola Mentor" :active="$isMentors" />
      <x-ui.sidebar.item href="{{ route('admin.mentor_verifications.index') }}" icon="fa-solid fa-user-check" label="Verifikasi Mentor" :active="$isMentorVerify" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Manage Transaksi">
      <x-ui.sidebar.item href="#" icon="fa-solid fa-credit-card" label="Berlangganan" />
      <x-ui.sidebar.item href="{{ route('admin.transactions.pending') }}" icon="fa-solid fa-money-check-dollar" label="Transaksi" :active="$isTransactions" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Interaksi">
      <x-ui.sidebar.item href="{{ route('admin.discussions.index') }}" icon="fa-solid fa-comments" label="Diskusi" />
      <x-ui.sidebar.item href="{{ route('admin.announcements.index') }}" icon="fa-solid fa-bullhorn" label="Pengumuman" />
    </x-ui.sidebar.section>

    
  </nav>
</div>
