<div class="flex items-center gap-3">
  @auth
    @php
      $role = auth()->user()->role ?? null;
      $href = $role === 'admin' ? route('admin.dashboard') : ($role === 'mentor' ? route('mentor.dashboard') : route('member.dashboard'));
    @endphp
    <a href="{{ $href }}"
       class="inline-flex items-center gap-2 rounded-xl border border-yellow-400/50 px-3 py-1.5
              text-yellow-300 hover:text-black hover:bg-yellow-400 transition-colors">
      <i class="fa-solid fa-gauge"></i>
      <span>Dashboard</span>
    </a>
  @else
    <a href="{{ route('login') }}"
       class="inline-flex items-center gap-2 rounded-xl border border-yellow-400/50 px-3 py-1.5
              text-yellow-300 hover:text-black hover:bg-yellow-400 transition-colors">
      <i class="fa-solid fa-right-to-bracket"></i>
      <span>Masuk</span>
    </a>
  @endauth
  
</div>
