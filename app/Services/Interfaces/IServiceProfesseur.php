<?php
use Illuminate\Database\Eloquent\Collection;
use App\Models\Utilisateurs\Professeur;

interface IServiceProfesseur extends IServiceUtilisateur {
    public function ajouterProfesseur(array $data): Professeur;
    public function definirDisponibilites(int $professeurId, array $disponibilites): bool;
    public function recupererCours(int $professeurId): Collection;
    public function recupererEmploiDuTemps(int $professeurId): Collection;
    public function calculerHeuresEffectuees(int $professeurId, int $moisId): int;
    public function recupererClasses(int $professeurId): Collection;
}