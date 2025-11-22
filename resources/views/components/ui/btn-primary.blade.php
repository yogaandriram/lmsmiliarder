@props([
  'href' => null,
  'type' => 'button',
  'icon' => null,
  'size' => 'md',
  'variant' => 'default', // default | danger
])

@php
  $sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2',
    'lg' => 'px-5 py-3 text-lg',
  ];
  $base = 'inline-flex items-center gap-2 rounded-lg font-semibold transition backdrop-blur-md shadow-inner';
  $styles = $variant === 'danger'
    ? 'text-white bg-gradient-to-b from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 ring-1 ring-red-400/30'
    : 'text-black bg-gradient-to-b from-yellow-400 to-amber-500 hover:from-yellow-300 hover:to-amber-400 ring-1 ring-white/20';
  $classes = trim($base.' '.($sizes[$size] ?? $sizes['md']).' '.$styles);
@endphp

@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
      <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
  </a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
      <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
  </button>
@endif