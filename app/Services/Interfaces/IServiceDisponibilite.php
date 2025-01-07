<?php
// app/Services/Interfaces/IDisponibiliteService.php
namespace App\Services\Interfaces;

use App\Models\Utilisateurs\Professeur;
use Carbon\Carbon;

interface IServiceDisponibilite
{
    /**
     * Vérifie la disponibilité d'un professeur pour une période donnée
     */
    public function verifierDisponibiliteProfesseur(Professeur $professeur, Carbon $dateDebut, Carbon $dateFin): bool;

    /**
     * Enregistre une nouvelle disponibilité pour un professeur
     */
    public function enregistrerDisponibilite(Professeur $professeur, Carbon $dateDebut, Carbon $dateFin, array $details): bool;

    /**
     * Récupère les disponibilités d'un professeur pour une période
     */
    public function getDisponibilites(Professeur $professeur, Carbon $dateDebut, Carbon $dateFin): array;

    /**
     * Met à jour la disponibilité d'un professeur
     */
    public function modifierDisponibilite(int $disponibiliteId, array $nouveauxDetails): bool;

    /**
     * Supprime une disponibilité
     */
    public function supprimerDisponibilite(int $disponibiliteId): bool;
    /**
     * Vérifie la disponibilité d'une salle
     */
    public function isSalleDisponible(int $salleId, string $date, string $heureDebut, string $heureFin): bool;

    /**
     * Vérifie la disponibilité d'un professeur
     */
    public function isProfesseurDisponible(int $professeurId, string $date, string $heureDebut, string $heureFin): bool;

    /**
     * Vérifie les conflits d'horaire pour une ressource
     */
    public function verifierConflit(string $date, string $heureDebut, string $heureFin, array $seances): bool;
}
