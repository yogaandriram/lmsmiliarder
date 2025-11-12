@props([
  'label' => null,
  'name' => 'role',
  'roles' => ['admin','mentor','member'],
  'selected' => null,
  'required' => false,
  'variant' => 'default', // default | glass
])

@php
  $baseDefault = 'w-full py-2 pl-3 rounded-lg border bg-white text-gray-900 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:border-white/10 dark:focus:ring-yellow-400';
  $baseGlass = 'w-full p-2 rounded bg-white/10 border border-white/20 text-white focus:ring-2 focus:ring-yellow-400';
  $baseClass = $variant === 'glass' ? $baseGlass : $baseDefault;
@endphp

@if($label)
  <label class="block text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
@endif
<div class="relative">
  <select name="{{ $name }}" @if($required) required @endif class="{{ $baseClass }}">
    @foreach($roles as $r)
      <option class="text-gray-900" value="{{ $r }}" {{ ($selected ?? '') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
    @endforeach
  </select>
@error($name)
  <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
@enderror
</div>