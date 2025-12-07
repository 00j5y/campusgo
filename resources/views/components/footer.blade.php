<footer class="bg-beige-second/50 pt-16 pb-8 border-t border-beige-second/30">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
            
            <div class="lg:col-span-5">
                <div class="flex shrink-0 items-center cursor-pointer">
                    <img class="h-12 w-auto" src="{{ asset('images/logo/logo.png') }}" alt="Campus Go Logo">
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
                    <li><a href="index.html/#howitworks" class="text-gris1 hover:text-vert-principale transition-colors">Comment ça marche ?</a></li>
                    <li><a href="index.html/#plateforme" class="text-gris1 hover:text-vert-principale transition-colors">Notre plateforme</a></li>
                    <li><a href="index.html/#communaute" class="text-gris1 hover:text-vert-principale transition-colors">Notre communauté</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4">
                <h3 class="text-noir font-semibold text-lg mb-6">Contact</h3>
                
                <a href="mailto:contact@campusgo.fr" class="flex items-center gap-3 text-gris1 hover:text-vert-principale transition-colors mb-6">
                    <img src="{{ asset('images/footer/logo-email.png') }}" class="size-5 mt-1">
                    contact@campusgo.fr
                </a>

                <div class="flex gap-4">
                    <a href="https://instagram.com/campusgo" class="text-gris1 hover:text-vert-principale transition-colors" target="_blank">
                        <img src="{{ asset('images/footer/logo-ig.png') }}" class="size-5 mt-1">
                    </a>
                    <a href="https://x.com/campusgo" class="text-gris1 hover:text-vert-principale transition-colors" target="_blank">
                        <img src="{{ asset('images/footer/logo-x.png') }}" class="size-5 mt-1">
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