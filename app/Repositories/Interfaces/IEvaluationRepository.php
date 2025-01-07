<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IEvaluationRepository extends IBaseRepository
{
    /**
     * Enregistrer une note
     * @param array $donnees
     * @return bool
     */
    public function enregistrerNote(array $donnees): bool;

    /**
     * Calculer la moyenne
     * @param int $etudiantId
     * @param int $moduleId
     * @return float
     */
    public function calculerMoyenne(int $etudiantId, int $moduleId): float;

    /**
     * Générer le bulletin
     * @param int $etudiantId
     * @param int $semestreId
     * @return array
     */
    public function genererBulletin(int $etudiantId, int $semestreId): array;
}