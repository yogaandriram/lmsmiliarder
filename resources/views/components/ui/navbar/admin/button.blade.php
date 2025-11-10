@props([
  'icon' => 'fa-regular fa-bell',
  'variant' => 'glass', // glass | gradient
  'href' => null,
])

@php
  $base = 'h-10 w-10 flex items-center justify-center rounded-xl';
  $isGradient = $variant === 'gradient';
  $classes = $isGradient
    ? $base.' bg-gradient-to-br from-yellow-500 to-orange-600 shadow-md'
    : $base.' glass glass-hover ring-1 ring-yellow-300/25';
  $iconColor = $isGradient ? 'text-black/90' : 'text-white';
@endphp

@if($href)
  <a href="{{ $href }}" class="{{ $classes }}">
    <i class="{{ $icon }} {{ $iconColor }}"></i>
  </a>
@else
  <button type="button" class="{{ $classes }}">
    <i class="{{ $icon }} {{ $iconColor }}"></i>
  </button>
@endif