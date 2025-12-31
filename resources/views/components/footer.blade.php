<footer class="bg-beige-second/50 pt-16 pb-8 border-t border-beige-second/30">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
            
            <div class="lg:col-span-5">
                <div class="flex shrink-0 items-center cursor-pointer">
                    <img class="h-12 w-auto" src="{{ asset('favicon.ico') }}" alt="Campus Go Logo">
                    <div class="ml-3 flex items-baseline text-2xl font-semibold font-sans">
                        <span class="text-vert-principale">Campus</span>
                        <span class="text-beige-second">'Go</span>
                    </div>
                </div>
                <div class="mt-5">
                    <p class="text-gris1 leading-relaxed max-w-sm">
                        Plateforme de covoiturage dédiée à l'IUT d'Amiens pour une mobilité durable et économique.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-3">
                <h3 class="text-noir font-semibold text-lg mb-6">Liens Utiles</h3>
                <ul class="space-y-4">
                    <li><a href="{{ url('/') }}" class="text-gris1 hover:text-vert-principale transition-colors">Accueil</a></li>
                    <li><a href="#" class="text-gris1 hover:text-vert-principale transition-colors">Rechercher</a></li>
                    <li><a href="#" class="text-gris1 hover:text-vert-principale transition-colors">Mes Trajets</a></li>
                    <li><a href="#" class="text-gris1 hover:text-vert-principale transition-colors">Profil</a></li>
                    <li><a href="{{ route('trajets.create') }}" class="text-gris1 hover:text-vert-principale transition-colors">Proposer un Trajet</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4">
                <h3 class="text-noir font-semibold text-lg mb-6">Contact</h3>
                
                <a href="mailto:contact@campusgo.fr" class="flex items-center gap-3 text-gris1 hover:text-vert-principale transition-colors mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    contact@campusgo.fr
                </a>

                <div class="flex gap-4 ml-0.5">
                    <a href="https://www.instagram.com/campusgo_amiens/" class="text-gris1 hover:text-vert-principale transition-colors" target="_blank">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.148 3.225-1.667 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069s-3.584-.011-4.85-.069c-3.225-.148-4.771-1.667-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.012-3.584.069-4.85c.148-3.225 1.667-4.771 4.919-4.919 1.265-.058 1.645-.069 4.85-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://x.com/campusgo_amiens" class="text-gris1 hover:text-vert-principale transition-colors" target="_blank">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        <div class="border-t border-gris2/30 pt-8 text-center">
            <p class="text-gris1 text-sm">
                2025 Campus'Go - IUT d'Amiens. Tous droits réservés.
            </p>
        </div>
    </div>
</footer>