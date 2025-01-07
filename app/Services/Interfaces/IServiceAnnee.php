<?php
// app/Services/Interfaces/IServiceAnnee.php
namespace App\Services\Interfaces;

use Carbon\Carbon;

interface IServiceAnnee
{
    /**
     * Crée une nouvelle année académique
     */
    public function creerAnneeAcademique(string $libelle, Carbon $dateDebut, Carbon $dateFin): bool;

    /**
     * Vérifie si une année académique est en cours
     */
    public function verifierAnneeEnCours(): bool;

    /**
     * Récupère l'année académique active
     */
    public function getAnneeActive(): ?object;

    /**
     * Clôture une année académique
     */
    public function cloturerAnnee(int $anneeId): bool;

    /**
     * Liste toutes les années académiques
     */
    public function listerAnnees(array $filtres = []): array;
}