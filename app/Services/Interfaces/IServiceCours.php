<?php
use Illuminate\Database\Eloquent\Collection;
use App\Models\Planification\Cours;


interface IServiceCours {
    public function planifier(array $data): Cours;
    public function modifierPlanification(int $coursId, array $data): Cours;
    public function annuler(int $coursId): bool;
    public function verifierConflits(array $data): bool;
    public function verifierQuotaHoraire(int $coursId): bool;
    public function recupererParModule(int $moduleId): Collection;
    public function recupererParProfesseur(int $professeurId): Collection;
    public function recupererParClasse(int $classeId): Collection;
    public function calculerProgression(int $coursId): float;
    public function terminerCours(int $coursId): bool;
}