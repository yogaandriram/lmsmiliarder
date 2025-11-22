@props([
  'label' => 'Stat',
  'value' => '-',
  'icon' => 'fa-solid fa-chart-simple',
  'size' => 'md',
])
@php
  $pad = $size === 'sm' ? 'p-3' : 'p-6';
  $iconBox = $size === 'sm' ? 'h-9 w-9' : 'h-12 w-12';
  $valueSize = $size === 'sm' ? 'text-xl' : 'text-3xl';
@endphp
<div class="glass {{ $pad }} rounded flex items-center gap-4">
  @if($icon)
  <div class="{{ $iconBox }} rounded-xl bg-linear-to-br from-yellow-500 to-orange-600 flex items-center justify-center shadow-md">
    <i class="{{ $icon }} text-black/90"></i>
  </div>
  @endif
  <div>
    <div class="text-sm text-yellow-300">{{ $label }}</div>
    <div class="{{ $valueSize }} font-bold">{{ $value }}</div>
  </div>
</div>