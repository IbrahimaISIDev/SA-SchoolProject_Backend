<?php

namespace App\Repositories\Interfaces;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Academique\AnneeAcademique;
use App\Models\Academique\Semestre;

interface IAnneeRepository extends IBaseRepository
{
    public function getCurrentAnnee();
    //public function getAnneesEnCours(): Collection;
    public function getActiveAnnees();
    public function updateStatus(int $id, string $status);
    public function getAnneesByStatut(string $statut): Collection;
    public function getSemestres(int $anneeId);

    /**
     * Trouve une année académique par son ID
     */
    public function find(int $id): ?AnneeAcademique;

    /**
     * Crée une nouvelle année académique
     */
    public function create(array $data): AnneeAcademique;

    /**
     * Récupère l'année académique en cours
     */
    public function getAnneeEnCours(): ?AnneeAcademique;

    /**
     * Met à jour le statut d'une année académique
     */
    public function updateStatut(int $anneeId, string $statut): bool;

    /**
     * Crée un nouveau semestre pour une année académique
     */
    public function createSemestre(int $anneeId, array $data): Semestre;

    /**
     * Vérifie si les dates d'un semestre chevauchent un semestre existant
     */
    public function checkSemestreChevauchement(int $anneeId, Carbon $dateDebut, Carbon $dateFin): bool;

    /**
     * Récupère toutes les années académiques avec filtres optionnels
     */
    public function getAllWithFilters(array $filtres = []): Collection;

    /**
     * Associe des classes à une année académique
     */
    public function attachClasses(int $anneeId, array $classesIds): void;

    /**
     * Vérifie si tous les cours d'une année sont terminés
     */
    public function verifierCoursTermines(int $anneeId): bool;
}