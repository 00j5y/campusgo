<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table Utilisateur
        Schema::create('utilisateur', function (Blueprint $table) {
            $table->id();
            $table->string('prenom', 32);
            $table->string('nom', 32);
            $table->string('email', 32)->unique();
            $table->string('num_tel', 10)->nullable();
            $table->string('mdp');
            $table->boolean('est_admin')->default(false);
        });

        // Table Véhicule
        Schema::create('vehicule', function (Blueprint $table) {
            $table->id();
            $table->string('marque', 16);
            $table->string('modele', 16);
            $table->string('couleur', 8);
            $table->tinyInteger('nombre_place');
            $table->string('immatriculation', 9);
            $table->foreignId('id_utilisateur')
                ->constrained('utilisateur')
                ->onDelete('cascade');
        });
        
        // Table Trajet
        Schema::create('trajet', function (Blueprint $table) {
            $table->id();
            $table->string('lieu_depart', 100);
            $table->string('lieu_arrivee', 100);
            $table->date('date_depart');
            $table->time('heure_depart');
            $table->time('heure_arrivee');
            $table->integer('place_disponible');
            $table->integer('prix');
            $table->foreignId('id_vehicule')
                  ->constrained('vehicule')
                  ->onDelete('cascade');
            $table->foreignId('id_utilisateur')
                  ->constrained('utilisateur')
                  ->onDelete('cascade');
        });

        // Table préférences
        Schema::create('preference', function (Blueprint $table) {
            $table->id();
            $table->boolean('accepte_animaux')->default(false);
            $table->boolean('accepte_fumeurs')->default(false);
            $table->boolean('accepte_musique')->default(true);
            $table->boolean('accepte_discussion')->default(true);
            $table->foreignId('id_utilisateur')
                ->constrained('utilisateur')
                ->onDelete('cascade');
        });

        // Table Avis
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('note');
            $table->text('commentaire')->nullable();
            $table->foreignId('id_trajet')
                ->constrained('trajet')
                ->onDelete('cascade');
            $table->foreignId('id_auteur')
                ->constrained('utilisateur')
                ->onDelete('cascade');
            $table->foreignId('id_destinataire')
                ->constrained('utilisateur')
                ->onDelete('cascade');
        });

        // Vérification pour qu'un utilisateur ne puisse pas ce mettre un avis lui même
        try {
            DB::statement('ALTER TABLE avis ADD CONSTRAINT check_auteur_destinataire CHECK (id_auteur <> id_destinataire)');
        } catch (\Exception $e) {}

        // Relation entre Trajet et Utilisateur 
        Schema::create('reserver', function (Blueprint $table) {
            $table->foreignId('id_utilisateur')
                ->constrained('utilisateur')
                ->onDelete('cascade');
            $table->foreignId('id_trajet')
                ->constrained('trajet')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserver');
        Schema::dropIfExists('avis');
        Schema::dropIfExists('preference');
        Schema::dropIfExists('trajet');
        Schema::dropIfExists('vehicule');
        Schema::dropIfExists('utilisateur');
    }
};