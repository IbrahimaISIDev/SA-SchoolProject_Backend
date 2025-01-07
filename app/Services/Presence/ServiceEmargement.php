<?php

use Carbon\Carbon;

// Services/Presence/ServiceEmargement.php
class ServiceEmargement implements IEmargementService 
{
    private $emargementRepository;
    
    public function __construct(IEmargementRepository $emargementRepository) 
    {
        $this->emargementRepository = $emargementRepository;
    }

    public function marquerPresence(int $etudiantId, int $seanceId): bool 
    {
        // Vérification du délai de 30 minutes
        $seance = Seance::findOrFail($seanceId);
        $heureDebut = Carbon::parse($seance->heure_debut);
        $maintenant = Carbon::now();
        
        if ($maintenant->diffInMinutes($heureDebut) > 30) {
            throw new \Exception('Le délai de 30 minutes est dépassé');
        }

        return $this->emargementRepository->create([
            'etudiant_id' => $etudiantId,
            'seance_id' => $seanceId,
            'heure_emargement' => $maintenant,
            'statut' => 'EN_ATTENTE'
        ]);
    }

    public function validerEmargements(int $seanceId): bool 
    {
        return $this->emargementRepository->validerParSeance($seanceId);
    }
}