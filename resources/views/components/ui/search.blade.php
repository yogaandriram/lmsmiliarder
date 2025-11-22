@props([
  'name' => 'q',
  'placeholder' => 'Cari...',
  'value' => null,
  'variant' => 'glass',
])
<x-ui.crud.input :label="null" :name="$name" type="text" icon="fa-solid fa-magnifying-glass" :placeholder="$placeholder" :value="$value" :variant="$variant" />