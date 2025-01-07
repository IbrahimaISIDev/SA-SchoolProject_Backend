<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moyennes_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('semestre_id')->constrained('semestres')->onDelete('cascade');
            $table->foreignId('annee_academique_id')->constrained('annees_academiques')->onDelete('cascade');
            $table->decimal('moyenne_devoir', 5, 2)->nullable();
            $table->decimal('moyenne_examen', 5, 2)->nullable();
            $table->decimal('moyenne_finale', 5, 2)->nullable();
            $table->decimal('coefficient', 3, 2)->default(1);
            $table->integer('credit')->default(1);
            $table->boolean('validation')->default(false);
            $table->enum('session', ['NORMALE', 'RATTRAPAGE'])->default('NORMALE');
            $table->timestamps();
            
            // Index pour améliorer les performances des requêtes
            $table->index(['etudiant_id', 'module_id', 'semestre_id']);
            $table->index('annee_academique_id');
            
            // Contrainte d'unicité pour éviter les doublons
            $table->unique([
                'etudiant_id', 
                'module_id', 
                'semestre_id', 
                'annee_academique_id',
                'session'
            ], 'moyenne_module_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moyennes_modules');
    }
};