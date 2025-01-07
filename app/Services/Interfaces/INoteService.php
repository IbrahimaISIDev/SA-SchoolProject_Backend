<?php
namespace App\Services\Interfaces;
namespace App\Services\Evaluation;


use Illuminate\Database\Eloquent\Collection;
use App\Models\Evaluation\Note;
use App\Models\Utilisateurs\Etudiant;
use App\Models\Evaluation\Devoir;
use App\Models\Evaluation\Examen;

// app/Services/Interfaces/INoteService.php
interface INoteService {
    public function saisir(array $data): Note;
    public function modifier(int $noteId, float $valeur): Note;
    public function supprimer(int $noteId): bool;
    public function calculerMoyenne(int $etudiantId, int $moduleId): float;
    public function recupererNotesEtudiant(int $etudiantId, int $semestreId): Collection;
    public function verifierValidation(int $etudiantId, int $moduleId): bool;

 /**
     * Enregistre une note pour un devoir
     */
    public function enregistrerNoteDevoir(Etudiant $etudiant, Devoir $devoir, float $note): bool;

    /**
     * Enregistre une note d'examen
     */
    public function enregistrerNoteExamen(Etudiant $etudiant, Examen $examen, float $note): bool;

    /**
     * Calcule la moyenne d'un module pour un étudiant
     */
    public function calculerMoyenneModule(Etudiant $etudiant, int $moduleId): float;

    /**
     * Génère le bulletin de notes d'un étudiant
     */
    public function genererBulletin(Etudiant $etudiant, int $semestreId): array;

    /**
     * Vérifie si toutes les notes sont saisies pour un devoir/examen
     */
    public function verifierSaisieComplete(int $evaluationId): bool;
}
