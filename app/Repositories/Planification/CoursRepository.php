<?php

namespace App\Repositories\Planification;

use App\Models\Planification\Cours;
use App\Models\Planification\Seance;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ICoursRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CoursRepository extends BaseRepository implements ICoursRepository
{
    public function __construct(Cours $model)
    {
        parent::__construct($model);
    }

    public function getCoursParProfesseur(int $professeurId): Collection
    {
        return $this->model->where('professeur_id', $professeurId)
            ->with(['module', 'classes', 'seances'])
            ->get();
    }

    public function getCoursParClasse(int $classeId): Collection
    {
        return $this->model->whereHas('classes', function ($query) use ($classeId) {
            $query->where('classe_id', $classeId);
        })
            ->with(['module', 'professeur', 'seances'])
            ->get();
    }

    public function getCoursParEtat(string $etat): Collection
    {
        return $this->model->where('statut', $etat)
            ->with(['module', 'professeur', 'classes', 'seances'])
            ->get();
    }

    public function verifierDisponibiliteRessources(array $donnees): bool
    {
        $dateDebut = Carbon::parse($donnees['date'] . ' ' . $donnees['heure_debut']);
        $dateFin = Carbon::parse($donnees['date'] . ' ' . $donnees['heure_fin']);

        // Vérifier la disponibilité du professeur
        $professeurOccupe = Seance::where('professeur_id', $donnees['professeur_id'])
            ->where(function ($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                    ->orWhereBetween('date_fin', [$dateDebut, $dateFin]);
            })->exists();

        if ($professeurOccupe) {
            return false;
        }

        // Vérifier la disponibilité de la salle
        $salleOccupee = Seance::where('salle_id', $donnees['salle_id'])
            ->where(function ($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                    ->orWhereBetween('date_fin', [$dateDebut, $dateFin]);
            })->exists();

        return !$salleOccupee;
    }

    public function calculerHeuresEffectuees(int $coursId): float
    {
        return $this->model->find($coursId)
            ->seances()
            ->where('statut', 'TERMINE')
            ->sum('nombre_heures');
    }

    public function filtrerParEtat(string $etat)
    {
        return $this->model->where('statut', $etat)
            ->with(['module', 'professeur', 'classes'])
            ->get();
    }

    public function filtrerParPeriode(string $debut, string $fin)
    {
        $dateDebut = Carbon::parse($debut)->startOfDay();
        $dateFin = Carbon::parse($fin)->endOfDay();

        return $this->model->whereHas('seances', function ($query) use ($dateDebut, $dateFin) {
            $query->whereBetween('date_debut', [$dateDebut, $dateFin]);
        })
            ->with(['module', 'professeur', 'classes', 'seances'])
            ->get();
    }

    public function getHeuresEffectuees(int $professeurId, int $moduleId)
    {
        return DB::table('seances')
            ->join('cours', 'seances.cours_id', '=', 'cours.id')
            ->where('cours.professeur_id', $professeurId)
            ->where('cours.module_id', $moduleId)
            ->where('seances.statut', 'TERMINE')
            ->sum('seances.nombre_heures');
    }

    public function verifierConflits(array $data): bool
    {
        return $this->model
            ->where('date_debut', '<=', $data['date_fin'])
            ->where('date_fin', '>=', $data['date_debut'])
            ->where('salle_id', $data['salle_id'])
            ->exists(); // Vérifie s'il y a des conflits
    }

    public function getByProfesseur(int $professeurId): array
    {
        return $this->model
            ->where('professeur_id', $professeurId) // Filtre par professeur
            ->orderBy('date_debut', 'asc')         // Tri par date de début
            ->get()
            ->toArray();                           // Retourne sous forme de tableau
    }
}
