<?php

namespace App\Services\Planification;

use App\Models\Utilisateurs\Professeur;
use App\Services\Interfaces\IServiceDisponibilite;
use App\Repositories\Interfaces\ISeanceRepository;
use App\Repositories\Interfaces\IDisponibiliteRepository;
use Carbon\Carbon;

class ServiceDisponibilite implements IServiceDisponibilite 
{
    private $seanceRepository;
    private $disponibiliteRepository;

    public function __construct(
        ISeanceRepository $seanceRepository,
        IDisponibiliteRepository $disponibiliteRepository
    ) {
        $this->seanceRepository = $seanceRepository;
        $this->disponibiliteRepository = $disponibiliteRepository;
    }

    public function verifierDisponibiliteProfesseur(
        Professeur $professeur, 
        Carbon $dateDebut, 
        Carbon $dateFin
    ): bool {
        // Vérifier les séances existantes
        $seances = $this->seanceRepository->getSeancesProfesseurPeriode(
            $professeur->id,
            $dateDebut,
            $dateFin
        );

        // Vérifier si les créneaux correspondent aux disponibilités déclarées
        $disponibilites = $this->disponibiliteRepository->getDisponibilites(
            $professeur->id,
            $dateDebut,
            $dateFin
        );

        // Si aucune disponibilité n'est déclarée pour cette période
        if ($disponibilites->isEmpty()) {
            return false;
        }

        // Vérifier s'il n'y a pas de conflit avec les séances existantes
        foreach ($seances as $seance) {
            if ($this->verifierConflit(
                $seance->date,
                $seance->heure_debut,
                $seance->heure_fin,
                $seances->except($seance->id)->toArray()
            )) {
                return false;
            }
        }

        // Vérifier si la période demandée est incluse dans les disponibilités
        foreach ($disponibilites as $disponibilite) {
            if ($dateDebut->between($disponibilite->date_debut, $disponibilite->date_fin) &&
                $dateFin->between($disponibilite->date_debut, $disponibilite->date_fin)) {
                return true;
            }
        }

        return false;
    }

    public function enregistrerDisponibilite(
        Professeur $professeur, 
        Carbon $dateDebut, 
        Carbon $dateFin, 
        array $details
    ): bool {
        // Vérifier si la période n'a pas de conflit avec d'autres disponibilités
        $disponibilitesExistantes = $this->disponibiliteRepository->getDisponibilites(
            $professeur->id,
            $dateDebut,
            $dateFin
        );

        foreach ($disponibilitesExistantes as $disponibilite) {
            if ($dateDebut->between($disponibilite->date_debut, $disponibilite->date_fin) ||
                $dateFin->between($disponibilite->date_debut, $disponibilite->date_fin)) {
                throw new \Exception("Cette période chevauche une disponibilité existante");
            }
        }

        return $this->disponibiliteRepository->create([
            'professeur_id' => $professeur->id,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'details' => $details
        ]);
    }

    public function getDisponibilites(
        Professeur $professeur, 
        Carbon $dateDebut, 
        Carbon $dateFin
    ): array {
        return $this->disponibiliteRepository->getDisponibilites(
            $professeur->id,
            $dateDebut,
            $dateFin
        )->toArray();
    }

    public function modifierDisponibilite(int $disponibiliteId, array $nouveauxDetails): bool 
    {
        $disponibilite = $this->disponibiliteRepository->find($disponibiliteId);
        
        if (!$disponibilite) {
            throw new \Exception("Disponibilité non trouvée");
        }

        return $this->disponibiliteRepository->update($disponibiliteId, $nouveauxDetails);
    }

    public function supprimerDisponibilite(int $disponibiliteId): bool 
    {
        $disponibilite = $this->disponibiliteRepository->find($disponibiliteId);
        
        if (!$disponibilite) {
            throw new \Exception("Disponibilité non trouvée");
        }

        // Vérifier s'il n'y a pas de séances planifiées sur cette disponibilité
        $seances = $this->seanceRepository->getSeancesProfesseurPeriode(
            $disponibilite->professeur_id,
            $disponibilite->date_debut,
            $disponibilite->date_fin
        );

        if (!$seances->isEmpty()) {
            throw new \Exception("Impossible de supprimer une disponibilité avec des séances planifiées");
        }

        return $this->disponibiliteRepository->delete($disponibiliteId);
    }

    public function isSalleDisponible(
        int $salleId, 
        string $date, 
        string $heureDebut, 
        string $heureFin
    ): bool {
        $seances = $this->seanceRepository->getSeancesSalle($salleId, $date);
        return !$this->verifierConflit($date, $heureDebut, $heureFin, $seances);
    }

    public function isProfesseurDisponible(
        int $professeurId, 
        string $date, 
        string $heureDebut, 
        string $heureFin
    ): bool {
        // Vérifier les séances existantes
        $seances = $this->seanceRepository->getSeancesProfesseur($professeurId, $date);
        
        // Si conflit avec des séances existantes
        if ($this->verifierConflit($date, $heureDebut, $heureFin, $seances)) {
            return false;
        }

        // Vérifier si le créneau est dans les disponibilités déclarées
        $dateDebut = Carbon::parse($date . ' ' . $heureDebut);
        $dateFin = Carbon::parse($date . ' ' . $heureFin);
        
        $disponibilites = $this->disponibiliteRepository->getDisponibilites(
            $professeurId,
            $dateDebut->startOfDay(),
            $dateFin->endOfDay()
        );

        foreach ($disponibilites as $disponibilite) {
            if ($dateDebut->between($disponibilite->date_debut, $disponibilite->date_fin) &&
                $dateFin->between($disponibilite->date_debut, $disponibilite->date_fin)) {
                return true;
            }
        }

        return false;
    }

    public function verifierConflit(
        string $date, 
        string $heureDebut, 
        string $heureFin, 
        array $seances
    ): bool {
        $debutPropose = Carbon::parse($date . ' ' . $heureDebut);
        $finPropose = Carbon::parse($date . ' ' . $heureFin);

        foreach ($seances as $seance) {
            $debutSeance = Carbon::parse($seance->date . ' ' . $seance->heure_debut);
            $finSeance = Carbon::parse($seance->date . ' ' . $seance->heure_fin);

            if ($debutPropose < $finSeance && $finPropose > $debutSeance) {
                return true; // Il y a conflit
            }
        }

        return false;
    }
}