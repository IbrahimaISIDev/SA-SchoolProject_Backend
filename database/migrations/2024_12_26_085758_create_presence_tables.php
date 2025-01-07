<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Émargements
        Schema::create('emargements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_id')->constrained();
            $table->foreignId('etudiant_id')->constrained('etudiants');
            $table->timestamp('heure_emargement');
            $table->boolean('valide_attache')->default(false);
            $table->timestamp('heure_validation_attache')->nullable();
            $table->timestamps();
            $table->unique(['seance_id', 'etudiant_id']);
        });

        // Absences
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_id')->constrained();
            $table->foreignId('etudiant_id')->constrained('etudiants');
            $table->integer('nombre_heures');
            $table->boolean('justifiee')->default(false);
            $table->timestamps();
        });

        // Justifications
        Schema::create('justifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absence_id')->constrained();
            $table->date('date_soumission');
            $table->text('motif');
            $table->string('piece_jointe')->nullable();
            $table->enum('statut', ['EN_ATTENTE', 'ACCEPTEE', 'REFUSEE']);
            $table->foreignId('validee_par')->nullable()->constrained('utilisateurs');
            $table->timestamp('date_validation')->nullable();
            $table->text('commentaire_validation')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('justifications');
        Schema::dropIfExists('absences');
        Schema::dropIfExists('emargements');
    }
};