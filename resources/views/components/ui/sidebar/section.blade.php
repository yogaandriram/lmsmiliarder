@props([
  'title' => null,
])

<div>
  @if($title)
    <div class="text-sm font-semibold text-yellow-400 mb-2">{{ $title }}</div>
  @endif
  {{ $slot }}
</div>