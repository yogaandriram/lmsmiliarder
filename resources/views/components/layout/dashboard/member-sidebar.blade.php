@props(['title' => 'EduLux LMS', 'subtitle' => 'Member Dashboard'])

<div class="space-y-6">
  <x-ui.sidebar.brand :title="$title" :subtitle="$subtitle" />

  <nav class="space-y-6">
    <x-ui.sidebar.section>
      <x-ui.sidebar.item href="{{ route('member.dashboard') }}" icon="fa-solid fa-gauge" label="Dashboard" :active="request()->routeIs('member.dashboard')" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Kelas Saya">
      <x-ui.sidebar.item href="{{ route('member.courses.index') }}" icon="fa-solid fa-book-open" label="Kursus" :active="request()->routeIs('member.courses.*')" />
      <x-ui.sidebar.item href="{{ route('member.ebooks.index') }}" icon="fa-solid fa-book" label="E-book" :active="request()->routeIs('member.ebooks.*')" />
      <x-ui.sidebar.item href="{{ route('member.discussions.index') }}" icon="fa-solid fa-comments" label="Diskusi" :active="request()->routeIs('member.discussions.*')" />
    </x-ui.sidebar.section>

    <x-ui.sidebar.section title="Transaksi & Langganan">
      <x-ui.sidebar.item href="{{ route('member.transactions.index') }}" icon="fa-solid fa-receipt" label="Transaksi" :active="request()->routeIs('member.transactions.*')" />
      <x-ui.sidebar.item href="{{ route('member.subscriptions.index') }}" icon="fa-solid fa-user-check" label="Berlangganan" :active="request()->routeIs('member.subscriptions.*')" />
    </x-ui.sidebar.section>
  </nav>
</div>
