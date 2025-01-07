<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Salles
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('numero')->unique();
            $table->integer('nombre_places');
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });

        // Cours
        Schema::create('cours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('professeur_id')->constrained('professeurs');
            $table->foreignId('semestre_id')->constrained();
            $table->integer('quota_horaire');
            $table->enum('statut', ['EN_COURS', 'TERMINE']);
            $table->timestamps();
        });

        // Relation Cours-Classes (plusieurs classes peuvent suivre le même cours)
        Schema::create('classe_cours', function (Blueprint $table) {
            $table->foreignId('classe_id')->constrained();
            $table->foreignId('cours_id')->constrained();
            $table->primary(['classe_id', 'cours_id']);
        });

        // Séances
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained();
            $table->foreignId('salle_id')->nullable()->constrained();
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->integer('nombre_heures');
            $table->enum('type', ['PRESENTIEL', 'EN_LIGNE']);
            $table->enum('statut', ['PLANIFIEE', 'EN_COURS', 'TERMINEE', 'ANNULEE']);
            $table->string('lien_virtuel')->nullable();
            $table->boolean('validee_attache')->default(false);
            $table->timestamps();
        });

        // Disponibilités des professeurs
        Schema::create('disponibilites_professeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained('professeurs');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('disponibilites_professeurs');
        Schema::dropIfExists('seances');
        Schema::dropIfExists('classe_cours');
        Schema::dropIfExists('cours');
        Schema::dropIfExists('salles');
    }
};