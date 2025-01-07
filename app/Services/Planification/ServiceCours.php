<?php

namespace App\Services;

use App\Models\Planification\Cours;
use App\Repositories\Interfaces\ICoursRepository;
use App\Services\Interfaces\IServiceCours;

use Carbon\Carbon;

class ServiceCours implements IServiceCours 
{
    private $coursRepository;

    public function __construct(ICoursRepository $coursRepository) 
    {
        $this->coursRepository = $coursRepository;
    }

    public function planifierCours(array $data): Cours 
    {
        // Vérification du quota horaire disponible
        $this->verifierQuotaHoraire($data['module_id'], $data['quota_horaire']);

        // Vérification de la disponibilité du professeur
        $this->verifierDisponibiliteProfesseur($data['professeur_id'], $data['semestre_id']);

        // Création du cours
        return $this->coursRepository->create([
            'module_id' => $data['module_id'],
            'professeur_id' => $data['professeur_id'],
            'quota_horaire' => $data['quota_horaire'],
            'semestre_id' => $data['semestre_id'],
            'statut' => 'EN_COURS'
        ]);
    }

    private function verifierQuotaHoraire(int $moduleId, int $quotaDemande): bool 
    {
        $quotaUtilise = $this->coursRepository->getQuotaHoraireUtilise($moduleId);
        if ($quotaUtilise + $quotaDemande > config('constants.QUOTA_MAX_MODULE')) {
            throw new \Exception('Quota horaire dépassé pour ce module');
        }
        return true;
    }

    public function filtrerParEtat(string $etat) 
    {
        return $this->coursRepository->filtrerParEtat($etat);
    }

    public function calculerHeuresEffectuees(int $coursId): array 
    {
        $cours = $this->coursRepository->findById($coursId);
        $heuresEffectuees = $cours->seances()
            ->where('statut', 'TERMINEE')
            ->sum('nombre_heures');

        return [
            'total' => $cours->quota_horaire,
            'effectuees' => $heuresEffectuees,
            'restantes' => $cours->quota_horaire - $heuresEffectuees
        ];
    }
}