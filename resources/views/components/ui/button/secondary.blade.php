@props([
  'href' => null,
  'type' => 'button',
  'icon' => null,
  'size' => 'md',
])

@php
  $sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2',
    'lg' => 'px-5 py-3 text-lg',
  ];
  $base = 'inline-flex items-center gap-2 rounded-lg font-medium transition backdrop-blur-md';
  $styles = 'text-white bg-white/10 hover:bg-white/20 border border-white/20 ring-1 ring-white/10 shadow-sm';
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