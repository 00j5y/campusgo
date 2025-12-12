@if (session('status'))
    @php
        $isError = session('status') === 'vehicle-deleted' || session('status') === 'user-deleted';
        $colorClass = $isError ? 'bg-red-100 border-red-400 text-red-700' : 'bg-green-100 border-green-400 text-green-700';
        $title = $isError ? 'Attention !' : 'Succès !';
    @endphp

    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
         class="mb-6 border px-4 py-3 rounded relative {{ $colorClass }}" role="alert">
        
        <strong class="font-bold">{{ $title }}</strong>

        <span class="block sm:inline ml-1">
            @switch(session('status'))
                @case('profile-updated') Votre profil a été mis à jour. @break
                @case('password-updated') Votre mot de passe a été modifié. @break
                @case('vehicle-added') Véhicule ajouté avec succès. @break
                @case('vehicle-deleted') Véhicule supprimé. @break
                @case('avatar-updated') Photo de profil mise à jour. @break
                @case('privacy-updated') Vos préférences de confidentialité sont enregistrées. @break
                @default Opération effectuée avec succès.
            @endswitch
        </span>
    </div>
@endif