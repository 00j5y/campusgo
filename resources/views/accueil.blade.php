@extends('layouts.app')

@section('title', 'Accueil - Campus\'GO')

@section('content')
    <main>
        <section class="relative bg-linear-to-br from-vert-principale/20 via-beige-second/50 to-beige-principale/50 pt-12 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 bg-vert-principale/20 px-4 py-1.5 rounded-full mb-6">
                            <span class="text-vert-principale text-sm font-semibold">
                                🎓 Pour la communauté IUT d'Amiens
                            </span>
                        </div>

                        <h1 class="text-4xl lg:text-5xl font-semibold text-noir leading-tight mb-6">
                            Voyagez <br class="hidden lg:block"/> ensemble vers <br class="hidden lg:block"/> l'IUT d'Amiens
                        </h1>

                        <p class="text-lg text-gris1 mb-8 leading-relaxed max-w-lg">
                            Campus'Go est la plateforme de covoiturage dédiée aux étudiants, enseignants et personnel de l'IUT d'Amiens. Partagez vos trajets, réduisez vos coûts et contribuez à un campus plus écologique.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 mb-12">
                            <a href="#" class="inline-flex justify-center items-center gap-2 bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-3.5 rounded-lg font-medium transition-colors shadow-sm">
                                Rechercher un Trajet
                                <img src="{{ asset('images/accueil/icones/fleche-droite.png') }}" alt="Fleche pointant vers la droite blanche" class="size-6 pt-0.5">
                            </a>
                            <a href="{{ route('trajets.create') }}" class="inline-flex justify-center items-center bg-white hover:white/20 text-gris1 hover:text-vert-principale px-6 py-3.5 rounded-lg font-medium transition-colors ">
                                Proposer un Trajet
                            </a>
                        </div>

                        <div class="grid grid-cols-3 gap-6 pt-8 border-t border-vert-principale/20">
                            <div>
                                <div class="text-3xl font-bold text-vert-principale">250+</div>
                                <div class="text-gris1 text-sm mt-1">Membres actifs</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-vert-principale">500+</div>
                                <div class="text-gris1 text-sm mt-1">Trajets partagés</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-vert-principale">2.5T</div>
                                <div class="text-gris1 text-sm mt-1">CO₂ économisé</div>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-full flex justify-end items-center pl-4 lg:pl-0 transition-transform duration-500 ease-in-out hover:scale-105">
                    <div class="relative w-full lg:w-[95%] h-[350px] sm:h-[450px] lg:h-[550px] rounded-3xl lg:rounded-[50px] overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/accueil/voiture.png') }}" alt="Étudiants en covoiturage" class="w-full h-full object-cover">   
                    </div>
                    <div class="absolute bottom-8 left-8 lg:p-b-16 xl:left-12 z-30 bg-white p-4 pr-8 rounded-2xl shadow-xl flex items-center gap-4 animate-fade-in-up max-w-[280px]">
                        <div class="w-12 h-12 rounded-full bg-vert-second flex items-center justify-center">
                            <img src="{{ asset('images/accueil/icones/feuille.png') }}" alt="icon" class="size-5.5">
                        </div>
                        <div>
                            <p class="text-sm text-gris1">Économie moyenne</p>
                            <p class="text-xl font-bold text-gray-900">45€/mois</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>

        <section id="howitworks" class="py-16 lg:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">

                <div class="text-center mb-16">
                    <h2 class="text-lg font-semibold text-noir mb-2">Comment ça marche ?</h2>
                    <p class="text-2xl lg:text-3xl text-gris1 font-medium">Trois étapes simples pour commencer votre covoiturage</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <div class="border border-gray-200 rounded-2xl p-8 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-300">
                        <div class="w-20 h-20 bg-vert-second text-vert-principale rounded-full flex items-center justify-center text-3xl font-bold mb-6">
                            1
                        </div>
                        <h3 class="text-xl font-bold text-noir mb-4">Inscrivez-vous</h3>
                        <p class="text-gris1 leading-relaxed">
                            Créez votre compte avec votre email IUT pour rejoindre la communauté Campus'Go.
                        </p>
                    </div>

                    <div class="border border-gray-200 rounded-2xl p-8 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-300">
                        <div class="w-20 h-20 bg-vert-second text-vert-principale rounded-full flex items-center justify-center text-3xl font-bold mb-6">
                            2
                        </div>
                        <h3 class="text-xl font-bold text-noir mb-4">Trouvez ou proposez</h3>
                        <p class="text-gris1 leading-relaxed">
                            Recherchez un trajet correspondant à vos besoins ou proposez le vôtre en quelques clics.
                        </p>
                    </div>

                    <div class="border border-gray-200 rounded-2xl p-8 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-300">
                        <div class="w-20 h-20 bg-vert-second text-vert-principale rounded-full flex items-center justify-center text-3xl font-bold mb-6">
                            3
                        </div>
                        <h3 class="text-xl font-bold text-noir mb-4">Voyagez ensemble</h3>
                        <p class="text-gris1 leading-relaxed">
                            Partagez le trajet avec d'autres membres de l'IUT et profitez d'un voyage convivial.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <section id="plateforme" class="py-16 lg:py-24 bg-beige-principale">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center mb-16">
                    <h2 class="text-lg font-semibold text-noir mb-2">Pourquoi choisir Campus'Go ?</h2>
                    <p class="text-2xl lg:text-3xl text-gris1 font-medium">Des avantages pour vous et pour la planète</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="bg-white border border-beige-second/50 rounded-2xl p-6 flex flex-col items-center text-center h-full hover:-translate-y-1 transition-transform duration-300 shadow-sm">
                        <div class="w-16 h-16 bg-vert-second text-vert-principale rounded-full flex items-center justify-center mb-6">
                            <img src="{{ asset('images/accueil/icones/cochon-economie.png') }}" alt="icon" class="size-7">
                        </div>
                        <h3 class="text-lg font-bold text-noir mb-3">Économies</h3>
                        <p class="text-sm text-gris1 leading-relaxed">
                            Partagez les frais de carburant et réduisez vos dépenses de transport jusqu'à 50%.
                        </p>
                    </div>

                    <div class="bg-white border border-beige-second/50 rounded-2xl p-6 flex flex-col items-center text-center h-full hover:-translate-y-1 transition-transform duration-300 shadow-sm">
                        <div class="w-16 h-16 bg-vert-second text-vert-principale rounded-full flex items-center justify-center mb-6">
                            <img src="{{ asset('images/accueil/icones/feuille.png') }}" alt="icon" class="size-5.5">
                        </div>
                        <h3 class="text-lg font-bold text-noir mb-3">Écologie</h3>
                        <p class="text-sm text-gris1 leading-relaxed">
                            Réduisez votre empreinte carbone et contribuez à un campus plus vert et durable.
                        </p>
                    </div>

                    <div class="bg-white border border-beige-second/50 rounded-2xl p-6 flex flex-col items-center text-center h-full hover:-translate-y-1 transition-transform duration-300 shadow-sm">
                        <div class="w-16 h-16 bg-vert-second text-vert-principale rounded-full flex items-center justify-center mb-6">
                            <img src="{{ asset('images/accueil/icones/bouclier-securite-vert.png') }}" alt="icon" class="size-7">
                        </div>
                        <h3 class="text-lg font-bold text-noir mb-3">Sécurité</h3>
                        <p class="text-sm text-gris1 leading-relaxed">
                            Voyagez en toute confiance avec des membres vérifiés de la communauté IUT.
                        </p>
                    </div>

                    <div class="bg-white border border-beige-second/50 rounded-2xl p-6 flex flex-col items-center text-center h-full hover:-translate-y-1 transition-transform duration-300 shadow-sm">
                        <div class="w-16 h-16 bg-vert-second text-vert-principale rounded-full flex items-center justify-center mb-6">
                            <img src="{{ asset('images/accueil/icones/personne-convivialite-vert.png') }}" alt="icon" class="size-7">
                        </div>
                        <h3 class="text-lg font-bold text-noir mb-3">Convivialité</h3>
                        <p class="text-sm text-gris1 leading-relaxed">
                            Rencontrez d'autres étudiants, enseignants et créez des liens sur la route.
                        </p>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-16 lg:py-24 bg-white overflow-hidden">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                    
                    <div class="w-full lg:w-1/2 flex justify-center lg:justify-end">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl max-w-md">
                            <img src="{{ asset('images/accueil/telephone.png') }}" alt="Application Campus'Go sur mobile" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-700">
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2">
                        <h2 class="text-sm font-semibold text-gris1 mb-4 tracking-wide">Une plateforme pensée pour vous</h2>
                        
                        <p class="text-lg text-gris1 mb-8 leading-relaxed">
                            Campus'Go a été conçu spécifiquement pour la communauté de </br> l'IUT d'Amiens avec des fonctionnalités adaptées à vos besoins.
                        </p>

                        <div class="space-y-6">
                            
                            <div class="flex gap-4">
                                <div class="mt-1">
                                    <img src="{{ asset('images/accueil/icones/valider.png') }}" class="size-5">
                                </div>
                                <div>
                                    <h3 class="font-bold text-noir">Recherche intelligente</h3>
                                    <p class="text-sm text-gris1 mt-1">Trouvez rapidement les trajets qui correspondent à vos horaires de cours</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="mt-1">
                                    <img src="{{ asset('images/accueil/icones/valider.png') }}" class="size-5">
                                </div>
                                <div>
                                    <h3 class="font-bold text-noir">Système de notation</h3>
                                    <p class="text-sm text-gris1 mt-1">Évaluez vos covoitureurs pour maintenir une communauté de confiance</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="mt-1">
                                    <img src="{{ asset('images/accueil/icones/valider.png') }}" class="size-5">
                                </div>
                                <div>
                                    <h3 class="font-bold text-noir">Notifications instantanées</h3>
                                    <p class="text-sm text-gris1 mt-1">Soyez alerté des nouveaux trajets et des demandes de réservation</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="mt-1">
                                    <img src="{{ asset('images/accueil/icones/valider.png') }}" class="size-5">
                                </div>
                                <div>
                                    <h3 class="font-bold text-noir">Flexible et simple</h3>
                                    <p class="text-sm text-gris1 mt-1">Annulez ou modifiez vos trajets facilement selon vos contraintes</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="communaute" class="py-16 lg:py-24 bg-linear-to-br from-beige-second/60 via-vert-second to-vert-principale/20">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col lg:flex-row items-center justify-center gap-12 lg:gap-10">
                    
                    <div class="w-full lg:w-5/12 flex flex-col justify-center">
                        <h2 class="text-lg font-semibold text-gris1 mb-4">Rejoignez une communauté engagée</h2>
                        
                        <p class="text-lg text-gris1 mb-8 leading-relaxed">
                            Plus de 250 étudiants, enseignants et membres du personnel de l'IUT d'Amiens utilisent déjà Campus'Go pour leurs trajets quotidiens.
                        </p>

                        <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-xl">
                            <div class="space-y-6">
                                
                                <div class="flex gap-4 items-start">
                                    <div class="w-10 h-10 rounded-full bg-vert-principale flex items-center justify-center shrink-0 text-white">
                                        <img src="{{ asset('images/accueil/icones/personne-convivialite-blanc.png') }}" alt="Icone Convivialité" class="size-5">
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-noir">Communauté IUT exclusive</h3>
                                        <p class="text-sm text-gris1 mt-1">Seuls les membres vérifiés de l'IUT peuvent rejoindre la plateforme</p>
                                    </div>
                                </div>

                                <div class="flex gap-4 items-start">
                                    <div class="w-10 h-10 rounded-full bg-vert-principale flex items-center justify-center shrink-0 text-white">
                                        <img src="{{ asset('images/accueil/icones/bouclier-securite-blanc.png') }}" alt="Icone Bouclier" class="size-5">
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-noir">Profils vérifiés</h3>
                                        <p class="text-sm text-gris1 mt-1">Tous les profils sont validés pour garantir votre sécurité</p>
                                    </div>
                                </div>

                                <div class="flex gap-4 items-start">
                                    <div class="w-10 h-10 rounded-full bg-vert-principale flex items-center justify-center shrink-0 text-white">
                                        <img src="{{ asset('images/accueil/icones/voiture-blanc.png') }}" alt="Icone Voiture" class="size-5">
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-noir">Support dédié</h3>
                                        <p class="text-sm text-gris1 mt-1">Une équipe disponible pour répondre à toutes vos questions</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-6/12 flex justify-center lg:justify-end">
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl max-w-lg w-full">
                            <img src="{{ asset('images/accueil/etudiant.png') }}" alt="Groupe étudiants" class="w-full h-auto object-cover hover:scale-105 transition-transform duration-700">
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="container mx-auto">
                <div class="rounded-4xl bg-linear-to-r from-vert-principale/20 to-beige-second/30 px-6 py-16 md:py-20 text-center relative overflow-hidden">
                    
                    <div class="relative z-10 max-w-3xl mx-auto">
                        <h2 class="text-3xl md:text-4xl font-semibold text-noir mb-6">
                            Prêt à commencer votre aventure ?
                        </h2>
                        
                        <p class="text-lg text-gris1 mb-10 leading-relaxed max-w-2xl mx-auto">
                            Rejoignez Campus'Go dès aujourd'hui et transformez vos trajets quotidiens en moments conviviaux et écologiques.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <a href="#" class="inline-flex justify-center items-center gap-2 bg-vert-principale hover:bg-vert-principal-h text-white px-8 py-3.5 rounded-lg font-medium transition-colors shadow-sm">
                                Commencer maintenant
                                <img src="{{ asset('images/accueil/icones/fleche-droite.png') }}" alt="Fleche pointant vers la droite blanche" class="size-6 pt-0.5">
                            </a>
                            
                            <a href="#" class="inline-flex justify-center items-center bg-white text-noir px-8 py-3.5 rounded-lg font-medium transition-colors shadow-sm">
                                En savoir plus
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
@endsection