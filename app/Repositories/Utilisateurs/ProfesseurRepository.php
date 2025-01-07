<?php

namespace App\Repositories;

use App\Models\Utilisateurs\Professeur;
use App\Repositories\Interfaces\IProfesseurRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfesseurRepository extends BaseRepository implements IProfesseurRepository
{
    /**
     * @var Professeur
     */
    protected $model;

    /**
     * ProfesseurRepository constructor.
     * 
     * @param Professeur $model
     */
    public function __construct(Professeur $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Récupère les professeurs disponibles pour une plage horaire donnée
     * 
     * @param string $date
     * @param string $heureDebut
     * @param string $heureFin
     * @return Collection
     */
    public function getProfesseursDisponibles(string $date, string $heureDebut, string $heureFin)
    {
        return $this->model
            ->whereNotExists(function ($query) use ($date, $heureDebut, $heureFin) {
                $query->select(DB::raw(1))
                    ->from('seances')
                    ->whereColumn('seances.professeur_id', 'professeurs.id')
                    ->where('seances.date', $date)
                    ->where(function ($q) use ($heureDebut, $heureFin) {
                        $q->whereBetween('seances.heure_debut', [$heureDebut, $heureFin])
                            ->orWhereBetween('seances.heure_fin', [$heureDebut, $heureFin]);
                    });
            })
            ->whereExists(function ($query) use ($date) {
                $query->select(DB::raw(1))
                    ->from('disponibilites')
                    ->whereColumn('disponibilites.professeur_id', 'professeurs.id')
                    ->where('disponibilites.jour', Carbon::parse($date)->format('l'))
                    ->where('disponibilites.est_disponible', true);
            })
            ->with(['specialites', 'disponibilites'])
            ->get();
    }

    /**
     * Récupère les heures de cours effectuées par un professeur pour un mois donné
     * 
     * @param int $professeurId
     * @param string $mois Format: 'YYYY-MM'
     * @return array
     */
    public function getHeuresEffectueesParMois(int $professeurId, string $mois)
    {
        $dateDebut = Carbon::createFromFormat('Y-m', $mois)->startOfMonth();
        $dateFin = Carbon::createFromFormat('Y-m', $mois)->endOfMonth();

        return DB::table('seances')
            ->where('professeur_id', $professeurId)
            ->whereBetween('date', [$dateDebut, $dateFin])
            ->where('statut', 'VALIDEE')
            ->select([
                'cours.module_id',
                DB::raw('SUM(TIMESTAMPDIFF(HOUR, heure_debut, heure_fin)) as heures_effectuees')
            ])
            ->join('cours', 'seances.cours_id', '=', 'cours.id')
            ->groupBy('cours.module_id')
            ->get()
            ->toArray();
    }

    /**
     * Récupère les disponibilités d'un professeur
     * 
     * @param int $professeurId
     * @return Collection
     */
    public function getDisponibilites(int $professeurId)
    {
        return DB::table('disponibilites')
            ->where('professeur_id', $professeurId)
            ->select([
                'jour',
                'heure_debut',
                'heure_fin',
                'est_disponible',
                'commentaire'
            ])
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get();
    }
}