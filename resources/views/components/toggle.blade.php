@props(['name', 'checked' => false, 'value' => '1'])

<label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" 
           name="{{ $name }}" 
           value="{{ $value }}"
           class="sr-only peer"
           {{ $checked ? 'checked' : '' }}
           {{ $attributes }} 
    >
    
    <div class="w-11 h-6 bg-gray-200 rounded-full peer 
                peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-vert-principale/30 
                peer-checked:bg-vert-principale 
                peer-checked:after:translate-x-full peer-checked:after:border-white 
                after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
    </div>
</label>