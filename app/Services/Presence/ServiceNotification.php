<?php

// app/Services/ServiceNotification.php
class ServiceNotification {
    protected $notificationRepository;

    public function __construct(INotificationRepository $notificationRepository) {
        $this->notificationRepository = $notificationRepository;
    }

    public function envoyerAvertissementAbsence(int $etudiantId) {
        $etudiant = $this->utilisateurRepository->find($etudiantId);
        
        return $this->notificationRepository->create([
            'type' => 'AVERTISSEMENT_ABSENCE',
            'utilisateur_id' => $etudiantId,
            'contenu' => "Vous avez atteint 10 heures d'absences.",
            'statut' => 'NON_LUE'
        ]);
    }

    public function envoyerConvocation(int $etudiantId) {
        $etudiant = $this->utilisateurRepository->find($etudiantId);
        
        return $this->notificationRepository->create([
            'type' => 'CONVOCATION',
            'utilisateur_id' => $etudiantId,
            'contenu' => "Vous avez dépassé 20 heures d'absences. Vous êtes convoqué(e).",
            'statut' => 'NON_LUE'
        ]);
    }

    public function notifierModificationSeance(int $seanceId, array $modifications) {
        $seance = $this->seanceRepository->find($seanceId);
        $etudiants = $seance->cours->classes->flatMap->etudiants;
        
        foreach ($etudiants as $etudiant) {
            $this->notificationRepository->create([
                'type' => 'MODIFICATION_SEANCE',
                'utilisateur_id' => $etudiant->id,
                'contenu' => "Modification de la séance du " . $seance->date,
                'statut' => 'NON_LUE'
            ]);
        }
    }
}