<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Évaluations (Devoirs/Examens)
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained();
            $table->string('libelle');
            $table->enum('type', ['DEVOIR', 'EXAMEN']);
            $table->date('date');
            $table->decimal('coefficient', 4, 2);
            $table->timestamps();
        });

        // Notes
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained();
            $table->foreignId('etudiant_id')->constrained('etudiants');
            $table->decimal('note', 5, 2);
            $table->timestamps();
            $table->unique(['evaluation_id', 'etudiant_id']);
        });

        // Bulletins
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants');
            $table->foreignId('semestre_id')->constrained();
            $table->decimal('moyenne_generale', 5, 2);
            $table->integer('rang');
            $table->text('appreciation')->nullable();
            $table->timestamps();
            $table->unique(['etudiant_id', 'semestre_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('bulletins');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('evaluations');
    }
};