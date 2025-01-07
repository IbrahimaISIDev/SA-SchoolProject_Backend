<?php

namespace App\Services\Interfaces;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Academique\Semestre;

interface IServiceSemestre {
    /**
     * Crée un nouveau semestre
     */
    public function creer(array $data): Semestre;

    /**
     * Modifie un semestre existant
     */
    public function modifier(int $id, array $data): Semestre;

    /**
     * Supprime un semestre
     */
    public function supprimer(int $id): bool;

    /**
     * Récupère les cours d'un semestre
     */
    public function recupererCours(int $semestreId): Collection;

    /**
     * Vérifie si une date est dans la période du semestre
     */
    public function verifierPeriode(int $semestreId, DateTime $date): bool;

    /**
     * Calcule la progression du semestre
     */
    public function calculerProgression(int $semestreId): float;

    /**
     * Planifie les modules pour un semestre
     */
    public function planifierModules(int $semestreId, array $modulesData): void;

    /**
     * Récupère le semestre actif pour une année académique
     */
    public function getSemestreActif(int $anneeId): ?Semestre;
}

