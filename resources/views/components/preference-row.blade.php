@props(['icon', 'title', 'subtitle' => ''])

<div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-vert-principale/30 transition-colors bg-gray-50/50">
    <div class="flex items-center gap-4">
        <div class="mt-1 shrink-0 text-vert-principale">
            <img src="{{ asset('images/profil/'.$icon.'.png') }}" alt="{{ $title }}" class="size-6 object-contain">
        </div>
        <div>
            <h4 class="font-semibold text-noir">{{ $title }}</h4>
            @if($subtitle) <p class="text-sm text-gris1">{{ $subtitle }}</p> @endif
        </div>
    </div>
    
    <div>
        {{ $slot }}
    </div>
</div>