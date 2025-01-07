<?php
namespace App\Services\Interfaces;

// app/Services/Interfaces/IBulletinService.php
use App\Models\Evaluation\Bulletin;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Utilisateurs\Etudiant;
use App\Models\Academique\Semestre;

interface IBulletinService {
    //public function generer(int $etudiantId, int $semestreId): Bulletin;
    public function calculerMoyenneGenerale(int $etudiantId, int $semestreId): float;
    //public function exporterPDF(int $bulletinId): string;
    public function recupererHistorique(int $etudiantId): Collection;
    public function publier(int $classeId, int $semestreId): bool;

    /**
     * Génère le bulletin de notes pour un étudiant et un semestre
     */
    public function genererBulletin(Etudiant $etudiant, Semestre $semestre): Bulletin;

    /**
     * Exporte le bulletin au format PDF
     */
    public function exporterPDF(Bulletin $bulletin): string;

    /**
     * Exporte le bulletin au format Excel
     */
    public function exporterExcel(Bulletin $bulletin): string;

    /**
     * Récupère tous les bulletins d'un étudiant
     */
    public function getBulletinsEtudiant(Etudiant $etudiant): Collection;

    /**
     * Récupère le bulletin d'un semestre spécifique
     */
    public function getBulletinSemestre(Etudiant $etudiant, Semestre $semestre): ?Bulletin;

    /**
     * Vérifie si le bulletin peut être généré
     */
    public function peutGenererBulletin(Etudiant $etudiant, Semestre $semestre): bool;

    /**
     * Valide le bulletin
     */
    public function validerBulletin(Bulletin $bulletin): bool;
}