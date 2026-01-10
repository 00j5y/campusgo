@props([
    'name', 
    'label', 
    'type' => 'text', 
    'value' => '', 
    'placeholder' => '', 
    'readonly' => false,
    'bag' => 'default'
])

<div class="w-full">
    <label for="{{ $name }}" class="block text-sm text-gris1 mb-2">
        {{ $label }}
    </label>
    
    <input 
        type="{{ $type }}" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        {{ $readonly ? 'readonly' : '' }}
        {{ $attributes->merge(['class' => 'w-full rounded-md px-2 border border-beige-second focus:outline-none focus:border-beige-principale focus:ring-2 focus:ring-beige-principale shadow-sm ' . ($readonly ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : '')]) }}
    >

    @error($name, $bag) 
        <span class="text-red-500 text-xs mt-1 block">
            <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}
        </span> 
    @enderror
</div>