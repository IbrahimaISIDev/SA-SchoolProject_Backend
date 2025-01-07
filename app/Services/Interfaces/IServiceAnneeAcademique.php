<?php
namespace App\Services\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Academique\AnneeAcademique;
use App\Models\Academique\Semestre;


interface IServiceAnneeAcademique {
    public function creer(array $data): AnneeAcademique;
    public function activer(int $anneeId): bool;
    public function cloturer(int $anneeId): bool;
    public function recupererEnCours(): ?AnneeAcademique;
    public function recupererSemestres(int $anneeId): Collection;
    public function ajouterSemestre(int $anneeId, array $data): Semestre;
    public function verifierChevauchement(array $data): bool;
        /**
     * Crée une nouvelle année académique
     */
    public function creerAnneeAcademique(string $libelle, Carbon $dateDebut, Carbon $dateFin): bool;

    /**
     * Vérifie si une année académique est en cours
     */
    public function verifierAnneeEnCours(): bool;

    /**
     * Récupère l'année académique active
     */
    public function getAnneeActive(): ?object;

    /**
     * Clôture une année académique
     */
    public function cloturerAnnee(int $anneeId): bool;

    /**
     * Liste toutes les années académiques
     */
    public function listerAnnees(array $filtres = []): array;

}