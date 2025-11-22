@props([
  'label' => 'Stat',
  'value' => '-',
  'icon' => 'fa-solid fa-chart-simple',
])
<div class="glass p-6 rounded flex items-center gap-4">
  @if($icon)
  <div class="h-12 w-12 rounded-xl bg-linear-to-br from-yellow-500 to-orange-600 flex items-center justify-center shadow-md">
    <i class="{{ $icon }} text-black/90"></i>
  </div>
  @endif
  <div>
    <div class="text-sm text-yellow-300">{{ $label }}</div>
    <div class="text-3xl font-bold">{{ $value }}</div>
  </div>
</div>