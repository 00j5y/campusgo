@php
    $message = null;
    $type = null;

    if (session('success')) {
        $message = session('success');
        $type = 'success';
        $colorClass = 'bg-green-100 border-green-400 text-green-700';
        $title = 'Succès !';
    } elseif (session('error')) {
        $message = session('error');
        $type = 'error';
        $colorClass = 'bg-red-100 border-red-400 text-red-700';
        $title = 'Erreur !';
    } elseif (session('status')) {
        $message = session('status'); // Pour les messages neutres ou par défaut de Laravel
        $type = 'info';
        $colorClass = 'bg-blue-100 border-blue-400 text-blue-700';
        $title = 'Information';
    }
@endphp

@if ($message)
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)" 
         class="mb-6 border px-4 py-3 rounded relative {{ $colorClass }} transition-opacity duration-500" 
         role="alert">
        
        <strong class="font-bold">{{ $title }}</strong>
        <span class="block sm:inline ml-1">{{ $message }}</span>
        
        {{-- Bouton pour fermer manuellement --}}
        <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
            <svg class="fill-current h-6 w-6 {{ $type === 'success' ? 'text-green-500' : ($type === 'error' ? 'text-red-500' : 'text-blue-500') }}" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Fermer</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
    </div>
@endif