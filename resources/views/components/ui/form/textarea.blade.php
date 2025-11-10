@props([
  'label' => null,
  'name',
  'rows' => 4,
  'variant' => 'default', // default | glass
])

@php
  $baseDefault = 'w-full py-2 px-3 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400';
  $baseGlass = 'w-full p-2 rounded bg-white/10 border border-white/20';
  $baseClass = $variant === 'glass' ? $baseGlass : $baseDefault;
@endphp

@if($label)
  <label class="block text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
@endif
<div class="relative">
  <textarea {{ $attributes->merge(['class' => $baseClass]) }} name="{{ $name }}" rows="{{ $rows }}">{{ $slot }}</textarea>
</div>
@error($name)
  <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
@enderror