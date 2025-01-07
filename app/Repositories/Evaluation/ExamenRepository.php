<?php

// app/Repositories/Evaluation/ExamenRepository.php
namespace App\Repositories\Evaluation;

use App\Models\Evaluation\Examen;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IExamenRepository;

class ExamenRepository extends BaseRepository implements IExamenRepository
{
    public function __construct(Examen $model)
    {
        parent::__construct($model);
    }

    public function getExamensByModule(int $moduleId)
    {
        return $this->model->where('module_id', $moduleId)
            ->with(['salle', 'surveillants'])
            ->orderBy('date', 'desc')
            ->get();
    }

    public function getExamensByPeriod(string $startDate, string $endDate)
    {
        return $this->model->whereBetween('date', [$startDate, $endDate])
            ->with(['module', 'salle', 'surveillants'])
            ->orderBy('date', 'asc')
            ->get();
    }

    public function getExamensByClass(int $classeId)
    {
        return $this->model->whereHas('classes', function($query) use ($classeId) {
            $query->where('classe_id', $classeId);
        })->with(['module', 'salle', 'surveillants'])
          ->orderBy('date', 'desc')
          ->get();
    }

    public function planifyExamen(array $examenData)
    {
        // Vérifier la disponibilité de la salle
        $salleDisponible = $this->checkSalleDisponibilite(
            $examenData['salle_id'],
            $examenData['date'],
            $examenData['heure_debut'],
            $examenData['heure_fin']
        );

        if (!$salleDisponible) {
            throw new \Exception("La salle n'est pas disponible pour cet horaire");
        }

        return $this->create($examenData);
    }

    private function checkSalleDisponibilite(int $salleId, string $date, string $heureDebut, string $heureFin)
    {
        return !$this->model->where('salle_id', $salleId)
            ->where('date', $date)
            ->where(function($query) use ($heureDebut, $heureFin) {
                $query->whereBetween('heure_debut', [$heureDebut, $heureFin])
                    ->orWhereBetween('heure_fin', [$heureDebut, $heureFin]);
            })->exists();
    }
}
