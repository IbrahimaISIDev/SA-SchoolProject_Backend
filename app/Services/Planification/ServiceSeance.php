<?php

namespace App\Services\Planification;

use DateTime;
use App\Models\Planification\Seance;
use App\Repositories\Interfaces\ISeanceRepository;
use App\Services\Interfaces\ISeanceService;

class ServiceSeance implements ISeanceService 
{
    private $seanceRepository;
    private $serviceDisponibilite;

    public function __construct(
        ISeanceRepository $seanceRepository,
        ServiceDisponibilite $serviceDisponibilite
    ) {
        $this->seanceRepository = $seanceRepository;
        $this->serviceDisponibilite = $serviceDisponibilite;
    }

    public function planifier(array $data): Seance 
    {
        $debut = new DateTime($data['date'] . ' ' . $data['heure_debut']);
        $fin = new DateTime($data['date'] . ' ' . $data['heure_fin']);

        // Vérification des conflits de ressources
        if (!$this->verifierDisponibilites($data['salle_id'], $data['professeur_id'], $debut, $fin)) {
            throw new \Exception('Conflit de disponibilité détecté');
        }

        // Vérification du respect du quota horaire
        if (!$this->seanceRepository->verifierQuotaRestant($data['cours_id'], $data['nombre_heures'])) {
            throw new \Exception('Quota horaire dépassé pour ce cours');
        }

        return $this->seanceRepository->create($data);
    }

    public function modifier(int $seanceId, array $data): Seance 
    {
        $seance = $this->seanceRepository->findById($seanceId);
        
        if (!$seance) {
            throw new \Exception('Séance non trouvée');
        }

        if ($seance->statut === 'TERMINEE') {
            throw new \Exception('Impossible de modifier une séance terminée');
        }

        $this->seanceRepository->update($seanceId, $data);
        return $this->seanceRepository->findById($seanceId);
    }

    public function annuler(int $seanceId, string $motif): bool 
    {
        $seance = $this->seanceRepository->findById($seanceId);
        
        if (!$seance) {
            throw new \Exception('Séance non trouvée');
        }

        if ($seance->statut === 'TERMINEE') {
            throw new \Exception('Impossible d\'annuler une séance terminée');
        }

        return $this->seanceRepository->update($seanceId, [
            'statut' => 'ANNULEE',
            'motif_annulation' => $motif
        ]);
    }

    public function valider(int $seanceId): bool 
    {
        $seance = $this->seanceRepository->findById($seanceId);
        
        if (!$seance) {
            throw new \Exception('Séance non trouvée');
        }

        if ($seance->statut !== 'PLANIFIEE') {
            throw new \Exception('Seule une séance planifiée peut être validée');
        }

        return $this->seanceRepository->update($seanceId, [
            'statut' => 'TERMINEE',
            'date_validation' => now()
        ]);
    }

    public function verifierDisponibiliteSalle(int $salleId, DateTime $debut, DateTime $fin): bool 
    {
        return $this->serviceDisponibilite->isSalleDisponible(
            $salleId,
            $debut->format('Y-m-d'),
            $debut->format('H:i'),
            $fin->format('H:i')
        );
    }

    public function verifierDisponibiliteProfesseur(int $professeurId, DateTime $debut, DateTime $fin): bool 
    {
        return $this->serviceDisponibilite->isProfesseurDisponible(
            $professeurId,
            $debut->format('Y-m-d'),
            $debut->format('H:i'),
            $fin->format('H:i')
        );
    }

    public function marquerPresences(int $seanceId, array $presences): bool 
    {
        $seance = $this->seanceRepository->findById($seanceId);
        
        if (!$seance) {
            throw new \Exception('Séance non trouvée');
        }

        if ($seance->statut !== 'PLANIFIEE') {
            throw new \Exception('Les présences ne peuvent être marquées que pour une séance planifiée');
        }

        return $this->seanceRepository->update($seanceId, [
            'presences' => $presences,
            'date_appel' => now()
        ]);
    }

    public function genererEmargement(int $seanceId): string 
    {
        $seance = $this->seanceRepository->findById($seanceId);
        
        if (!$seance) {
            throw new \Exception('Séance non trouvée');
        }

        // Logique de génération du document d'émargement
        // À implémenter selon vos besoins spécifiques
        return "path/to/emargement.pdf";
    }

    private function verifierDisponibilites(
        int $salleId,
        int $professeurId,
        DateTime $debut,
        DateTime $fin
    ): bool {
        return $this->verifierDisponibiliteSalle($salleId, $debut, $fin) &&
               $this->verifierDisponibiliteProfesseur($professeurId, $debut, $fin);
    }
}