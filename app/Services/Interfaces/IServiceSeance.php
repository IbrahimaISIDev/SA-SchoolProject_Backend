<?php
use App\Models\Planification\Seance;
use DateTime;

interface IServiceSeance {
    public function planifier(array $data): Seance;
    public function modifier(int $seanceId, array $data): Seance;
    public function annuler(int $seanceId, string $motif): bool;
    public function valider(int $seanceId): bool;
    public function verifierDisponibiliteSalle(int $salleId, DateTime $debut, DateTime $fin): bool;
    public function verifierDisponibiliteProfesseur(int $professeurId, DateTime $debut, DateTime $fin): bool;
    public function marquerPresences(int $seanceId, array $presences): bool;
    public function genererEmargement(int $seanceId): string;
}