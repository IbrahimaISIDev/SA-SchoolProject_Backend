<?php
// app/Repositories/Interfaces/ICoursRepository.php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ICoursRepository extends IBaseRepository
{
    public function getCoursParProfesseur(int $professeurId): Collection;
    public function getCoursParClasse(int $classeId): Collection;
    public function getCoursParEtat(string $etat): Collection;
    public function verifierDisponibiliteRessources(array $donnees): bool;
    public function calculerHeuresEffectuees(int $coursId): float;
    public function filtrerParEtat(string $etat);
    public function filtrerParPeriode(string $debut, string $fin);
    public function getHeuresEffectuees(int $professeurId, int $moduleId);
    public function getByProfesseur(int $professeurId); // Ajout de la méthode
    public function verifierConflits(array $data): bool;
    //public function getByProfesseur(int $professeurId): array; // Méthode définie dans l'interface


}
