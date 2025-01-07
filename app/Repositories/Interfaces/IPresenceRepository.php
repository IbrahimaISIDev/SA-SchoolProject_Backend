<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IPresenceRepository extends IBaseRepository
{
    /**
     * Enregistrer l'émargement d'un étudiant
     * @param int $etudiantId
     * @param int $seanceId
     * @return bool
     */
    public function enregistrerEmargement(int $etudiantId, int $seanceId): bool;

    /**
     * Valider les émargements d'une séance
     * @param int $seanceId
     * @return bool
     */
    public function validerEmargements(int $seanceId): bool;

    /**
     * Calculer les heures d'absence
     * @param int $etudiantId
     * @param int $semestreId
     * @return float
     */
    public function calculerHeuresAbsence(int $etudiantId, int $semestreId): float;

    /**
     * Vérifier le seuil d'absence
     * @param int $etudiantId
     * @return array
     */
    public function verifierSeuilAbsence(int $etudiantId): array;
}