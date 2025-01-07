<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('photo')->nullable();
            $table->string('password');
            $table->enum('type_utilisateur', ['ETUDIANT', 'PROFESSEUR', 'RESPONSABLE', 'ATTACHE']);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        // Table spécifique pour les étudiants
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->date('date_naissance');
            $table->string('lieu_naissance');
            $table->string('adresse');
            $table->foreignId('classe_id')->nullable()->constrained('classes');
            $table->timestamps();
        });

        // Table spécifique pour les professeurs
        Schema::create('professeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('specialite');
            $table->string('grade');
            $table->date('date_embauche');
            $table->string('cv')->nullable();
            $table->timestamps();
        });

        // Table spécifique pour les responsables pédagogiques
        Schema::create('responsables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('fonction');
            $table->date('date_prise_poste');
            $table->timestamps();
        });

        // Table spécifique pour les attachés
        Schema::create('attaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('fonction');
            $table->date('date_embauche');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('attaches');
        Schema::dropIfExists('responsables');
        Schema::dropIfExists('professeurs');
        Schema::dropIfExists('etudiants');
        Schema::dropIfExists('utilisateurs');
    }
};