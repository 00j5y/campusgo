@props(['name', 'value' => 3, 'autosubmit' => false])

<div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 mt-4">
    <div class="flex items-center gap-4 mb-3">
        <div class="mt-1 shrink-0 text-vert-principale">
            <img src="{{ asset('images/profil/discussion.png') }}" alt="Discussion" class="size-6 object-contain">
        </div>
        <div>
            <h4 class="font-semibold text-noir">Envie de discuter ?</h4>
            <p class="text-sm text-gris1">Quel genre de personne es-tu ?</p>
        </div>
    </div>

    <div class="px-2">
        <div class="relative w-full">
            <div class="flex justify-between text-xs font-medium text-gris1 mb-2">
                <span>Très Timide</span>
                <span>Timide</span>
                <span>Ni l'un ni l'autre</span>
                <span>Bavard</span>
                <span>Très Bavard</span>
            </div>

            <input type="range" 
                   name="{{ $name }}" 
                   min="1" max="5" step="1" 
                   value="{{ $value }}" 
                   @if($autosubmit) onchange="this.form.submit()" @endif
                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                   style="accent-color: #68A35E;"> 
            
            <div class="flex justify-between w-full px-1 mt-1">
                @for ($i = 0; $i < 5; $i++)
                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                @endfor
            </div>
        </div>
    </div>
</div>