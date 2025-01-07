<?php

// app/Repositories/Planification/SeanceRepository.php
namespace App\Repositories\Planification;

use App\Models\Planification\Seance;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ISeanceRepository;
use Carbon\Carbon;

class SeanceRepository extends BaseRepository implements ISeanceRepository
{
    public function __construct(Seance $model)
    {
        parent::__construct($model);
    }

    public function planifierSeance(array $data)
    {
        // Vérifier les conflits avant de planifier
        if ($this->verifierConflitsHoraires($data)) {
            return false;
        }

        return $this->create($data);
    }

    public function annulerSeance(int $seanceId, string $motif)
    {
        return $this->model->find($seanceId)->update([
            'statut' => 'ANNULE',
            'motif_annulation' => $motif,
            'date_annulation' => now()
        ]);
    }

    public function validerSeance(int $seanceId)
    {
        return $this->model->find($seanceId)->update([
            'statut' => 'VALIDE',
            'date_validation' => now()
        ]);
    }


    public function getSeancesParCours(int $coursId)
    {
        return $this->model
            ->where('cours_id', $coursId)
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();
    }

    public function verifierConflitsHoraires(array $data)
    {
        $start = Carbon::parse($data['date'] . ' ' . $data['heure_debut']);
        $end = Carbon::parse($data['date'] . ' ' . $data['heure_fin']);

        // Vérifier les conflits pour la salle
        $salleConflict = $this->model
            ->where('salle_id', $data['salle_id'])
            ->where('date', $data['date'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<=', $start)
                        ->where('heure_fin', '>', $start);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<', $end)
                        ->where('heure_fin', '>=', $end);
                });
            })
            ->exists();

        // Vérifier les conflits pour le professeur
        $profConflict = $this->model
            ->whereHas('cours', function ($query) use ($data) {
                $query->where('professeur_id', $data['professeur_id']);
            })
            ->where('date', $data['date'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<=', $start)
                        ->where('heure_fin', '>', $start);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<', $end)
                        ->where('heure_fin', '>=', $end);
                });
            })
            ->exists();

        return $salleConflict || $profConflict;
    }

    public function valider(int $seanceId)
    {
        return $this->model->find($seanceId)->update([
            'statut' => 'VALIDE',
            'date_validation' => now()
        ]);
    }

    public function getHeuresEffectueesParMois(int $professeurId, int $moisId)
    {
        return $this->model
            ->where('professeur_id', $professeurId)
            ->whereMonth('date', $moisId)
            ->sum('duree'); // Supposons qu'il y ait une colonne 'duree'
    }

    public function demanderAnnulation(int $seanceId, string $motif)
    {
        $seance = $this->model->find($seanceId);
        $seance->update([
            'statut' => 'En attente d\'annulation',
            'motif_annulation' => $motif,
        ]);
        return $seance;
    }
}
