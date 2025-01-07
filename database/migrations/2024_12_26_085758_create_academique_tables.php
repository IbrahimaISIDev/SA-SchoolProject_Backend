<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Années académiques
        Schema::create('annees_academiques', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->unique();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['EN_COURS', 'TERMINEE']);
            $table->timestamps();
        });

        // Semestres
        Schema::create('semestres', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->foreignId('annee_academique_id')->constrained()->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->timestamps();
        });

        // Filières
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Niveaux
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->unique();
            $table->timestamps();
        });

        // Classes
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->foreignId('filiere_id')->constrained();
            $table->foreignId('niveau_id')->constrained();
            $table->foreignId('annee_academique_id')->constrained();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->unique(['libelle', 'annee_academique_id']);
        });

        // Modules
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('modules');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('niveaux');
        Schema::dropIfExists('filieres');
        Schema::dropIfExists('semestres');
        Schema::dropIfExists('annees_academiques');
    }
};