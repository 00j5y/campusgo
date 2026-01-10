@props(['user', 'textSize' => 'text-3xl'])

<div {{ $attributes->merge(['class' => 'rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center shrink-0']) }}>
    
    @if($user && $user->photo)
        {{-- CAS 1 : Photo existante --}}
        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->prenom }}" class="w-full h-full object-cover">
    @else
        {{-- CAS 2 : Initiales --}}
        <span class="{{ $textSize }} font-bold text-gray-500 uppercase select-none">
            {{-- substr(..., 0, 1) prend la première lettre --}}
            {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
        </span>
    @endif

</div>