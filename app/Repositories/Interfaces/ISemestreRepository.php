<?php
namespace App\Repositories\Interfaces;

use App\Models\Academique\Semestre;
use Illuminate\Database\Eloquent\Collection;

interface ISemestreRepository extends IBaseRepository
{
    public function getCurrentSemestre();
    public function getSemestresByAnnee(int $anneeId);
    public function updateStatus(int $id, string $status);
    public function getModulesBySemestre(int $semestreId);
    public function getCoursForSemestre(int $semestreId);

    /**
     * Trouve un semestre par son ID
     */
    public function find(int $id): ?Semestre;

    /**
     * Crée un nouveau semestre
     */
    public function create(array $data): Semestre;

    /**
     * Met à jour un semestre
     */
    //public function update(int $id, array $data): Semestre;

    /**
     * Supprime un semestre
     */
    public function delete(int $id): bool;

    /**
     * Vérifie s'il y a chevauchement avec d'autres semestres
     */
    public function existeChevauchement(array $data): bool;

    /**
     * Attache des modules à un semestre
     */
    public function attachModules(int $semestreId, array $modulesData): void;

    /**
     * Récupère le semestre actif pour une année académique
     */
    public function getSemestreActif(int $anneeId): ?Semestre;

    /**
     * Récupère les cours d'un semestre
     */
    public function getCours(int $semestreId): Collection;
}