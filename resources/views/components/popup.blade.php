@props([
    'id', 
    'title', 
    'message', 
    'action' => '', 
    'method' => 'POST', 
    'type' => 'danger',
    'confirmText' => 'Confirmer',
    'cancelText' => 'Retour',
    'icon' => null
])

@php
    // Configuration des styles selon le type d'alerte
    $styles = [
        'danger' => [
            'icon_bg' => 'bg-red-100',
            'icon_text' => 'text-red-600',
            'btn_bg' => 'bg-red-500 hover:bg-red-600 text-white',
            'default_icon' => 'fa-triangle-exclamation'
        ],
        'success' => [
            'icon_bg' => 'bg-green-100',
            'icon_text' => 'text-vert-principale',
            'btn_bg' => 'bg-vert-principale hover:bg-vert-principal-h text-white',
            'default_icon' => 'fa-check'
        ],
        'warning' => [
            'icon_bg' => 'bg-orange-100',
            'icon_text' => 'text-orange-500',
            'btn_bg' => 'bg-orange-500 hover:bg-orange-600 text-white',
            'default_icon' => 'fa-circle-exclamation'
        ],
        'info' => [ // Pour simple confirmation neutre
            'icon_bg' => 'bg-blue-100',
            'icon_text' => 'text-blue-500',
            'btn_bg' => 'bg-blue-500 hover:bg-blue-600 text-white',
            'default_icon' => 'fa-info'
        ]
    ];

    $style = $styles[$type] ?? $styles['danger'];
    $currentIcon = $icon ?? $style['default_icon'];
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-[9999] hidden modal-component" aria-labelledby="modal-title-{{ $id }}" role="dialog" aria-modal="true">
    {{-- Backdrop (Fond sombre flouté) --}}
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('{{ $id }}')"></div>

    {{-- Conteneur Modal --}}
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center p-4">
        
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center transform transition-all scale-100 animate-fade-in-up">
            
            {{-- Icône --}}
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full {{ $style['icon_bg'] }} mb-4">
                <i class="fa-solid {{ $currentIcon }} text-2xl {{ $style['icon_text'] }}"></i>
            </div>

            {{-- Titre et Message --}}
            <h3 class="text-xl font-bold mb-2 text-gray-800">{{ $title }}</h3>
            <p class="text-gray-500 text-sm mb-6">{{ $message }}</p>

            {{-- Formulaire d'action --}}
            <form id="form-{{ $id }}" action="{{ $action }}" method="POST" class="flex gap-3 justify-center w-full">
                @csrf
                <input type="hidden" name="_method" value="{{ strtoupper($method) }}">

                <div class="mb-6 text-left">
                    {{ $slot }}
                </div>

                <button type="button" onclick="closeModal('{{ $id }}')" class="cursor-pointer flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-bold transition">
                    {{ $cancelText }}
                </button>
                
                <button type="submit" class="cursor-pointer flex-1 {{ $style['btn_bg'] }} px-4 py-3 rounded-xl font-bold transition shadow-lg">
                    {{ $confirmText }}
                </button>
            </form>
        </div>
    </div>
</div>