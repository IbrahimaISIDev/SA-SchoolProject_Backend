<?php
// app/Services/Interfaces/INotificationService.phpd
namespace App\Services\Interfaces;

use App\Models\Utilisateurs\Utilisateur;

interface INotificationService
{
    /**
     * Envoie une notification d'avertissement pour absences
     */
    public function envoyerAvertissementAbsences(Utilisateur $utilisateur, int $heuresAbsences): bool;

    /**
     * Envoie une convocation pour dépassement du seuil d'absences
     */
    public function envoyerConvocation(Utilisateur $utilisateur, int $heuresAbsences): bool;

    /**
     * Notifie une modification de séance
     */
    public function notifierModificationSeance(array $utilisateurs, array $detailsModification): bool;

    /**
     * Envoie une notification par SMS
     */
    public function envoyerSMS(string $numero, string $message): bool;

    /**
     * Envoie une notification par email
     */
    public function envoyerEmail(string $email, string $sujet, string $contenu): bool;
}

