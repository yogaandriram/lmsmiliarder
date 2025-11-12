@props([
  'label' => null,
  'name',
  'type' => 'text',
  'icon' => null,
  'placeholder' => null,
  'value' => null,
  'required' => false,
  'showToggle' => false,
])

@php
  $hasIcon = (bool) $icon;
  // Styling khusus halaman auth, sedikit lebih ringkas
  $baseClass = 'w-full py-2 '.($hasIcon ? 'pl-9' : 'pl-3').' pr-3 rounded-lg border bg-white text-gray-900 placeholder-gray-400 border-gray-300 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-neutral-900 dark:text-gray-100 dark:placeholder-gray-500 dark:border-white/10 dark:focus:ring-yellow-400';
@endphp

@if($label)
  <label class="block text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
@endif
<div class="relative">
  @if($icon)
    <i class="{{ $icon }} absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
  @endif
  <input
    {{ $attributes->merge(['class' => $baseClass]) }}
    type="{{ $type }}"
    name="{{ $name }}"
    @if(!is_null($value)) value="{{ $value }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
  />

  @if($showToggle)
    <button type="button" aria-label="Tampilkan/Sembunyikan" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-yellow-500 dark:text-gray-400 dark:hover:text-yellow-400" onclick="toggleAuthInputVisibility(this)">
      <i class="fa-solid fa-eye"></i>
    </button>
  @endif
</div>
@error($name)
  <p class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
@enderror

<script>
// Hindari redefinisi saat komponen dirender berkali-kali
(function(){
  if(window.__auth_toggle_loaded) return;
  window.__auth_toggle_loaded = true;
  window.toggleAuthInputVisibility = function(btn){
    const input = btn.previousElementSibling;
    if(!input) return;
    const icon = btn.querySelector('i');
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    if(icon){
      icon.classList.toggle('fa-eye', !show);
      icon.classList.toggle('fa-eye-slash', show);
    }
  }
})();
</script>