@props([
  'icon' => null,
  'label' => null,
])

@php
  $classes = 'block w-full px-4 py-2 rounded text-white hover:text-yellow-400 text-left';
@endphp

<button class="{{ $classes }}">
  <span class="inline-flex items-center gap-2">
    @if($icon)
      <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $label }}</span>
  </span>
</button>