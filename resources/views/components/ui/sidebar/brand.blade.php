@props([
  'title' => 'EduLux LMS',
  'subtitle' => 'Student Dashboard',
])

<div class="flex items-center justify-between">
  <div class="flex items-center gap-3">
    <span class="inline-flex items-center justify-center w-10 h-10 bg-yellow-500 rounded-lg text-black">
      <i class="fa-solid fa-graduation-cap"></i>
    </span>
    <div>
      <div class="font-bold text-lg">{{ $title }}</div>
      <div class="text-xs text-white/70">{{ $subtitle }}</div>
    </div>
  </div>
  <i class="fa-solid fa-gear text-white/70"></i>
  </div>