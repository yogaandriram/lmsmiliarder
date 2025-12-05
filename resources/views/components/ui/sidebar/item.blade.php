@props([
  'href' => '#',
  'icon' => null,
  'label' => null,
  'active' => false,
])

@php
  $linkBase = 'block w-full px-4 py-2 rounded text-white hover:text-yellow-400';
  $activeLink = 'block w-full px-4 py-2 rounded-full bg-linear-to-r from-yellow-500 to-orange-400 text-black';
  $classes = $active ? $activeLink : $linkBase;
@endphp

<a href="{{ $href }}" class="{{ $classes }}">
  <span class="inline-flex items-center gap-2">
    @if($icon)
      <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $label }}</span>
  </span>
</a>
