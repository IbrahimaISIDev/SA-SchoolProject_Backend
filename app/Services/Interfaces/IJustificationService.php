<?php
// app/Services/Interfaces/IJustificationService.php
namespace App\Services\Interfaces;

use App\Models\Utilisateurs\Etudiant;
use App\Models\Presence\Absence;
use Illuminate\Http\UploadedFile;

interface IJustificationService
{
    /**
     * Soumet une justification pour une absence
     */
    public function soumettreJustification(Absence $absence, string $motif, ?UploadedFile $pieceJointe): bool;

    /**
     * Valide une justification par l'attaché
     */
    public function validerJustification(int $justificationId, string $decision, ?string $commentaire): bool;

    /**
     * Récupère les justifications d'un étudiant
     */
    public function getJustificationsEtudiant(Etudiant $etudiant): array;

    /**
     * Récupère les justifications en attente de validation
     */
    public function getJustificationsEnAttente(): array;

    /**
     * Vérifie si une absence peut encore être justifiée
     */
    public function peutEtreJustifiee(Absence $absence): bool;
}