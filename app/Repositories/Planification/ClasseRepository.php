<?php

namespace App\Repositories\Planification;

use App\Models\Planification\Classe;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IClasseRepository;
use Illuminate\Database\Eloquent\Collection;

class ClasseRepository extends BaseRepository implements IClasseRepository
{
    public function __construct(Classe $model)
    {
        parent::__construct($model);
    }

    /**
     * Récupère toutes les classes d'une filière donnée
     * @param int $filiereId
     * @return Collection
     */
    public function getClassesByFiliere(int $filiereId)
    {
        return $this->model
            ->where('filiere_id', $filiereId)
            ->with(['niveau', 'filiere'])
            ->get();
    }

    /**
     * Récupère toutes les classes d'un niveau donné
     * @param int $niveauId
     * @return Collection
     */
    public function getClassesByNiveau(int $niveauId)
    {
        return $this->model
            ->where('niveau_id', $niveauId)
            ->with(['niveau', 'filiere'])
            ->get();
    }

    /**
     * Récupère une classe avec tous ses étudiants
     * @param int $classeId
     * @return Classe|null
     */
    public function getClassWithStudents(int $classeId)
    {
        return $this->model
            ->with(['etudiants' => function ($query) {
                $query->orderBy('nom')->orderBy('prenom');
            }])
            ->findOrFail($classeId);
    }

    /**
     * Récupère l'emploi du temps d'une classe
     * @param int $classeId
     * @return Collection
     */
    public function getClassSchedule(int $classeId)
    {
        return $this->model
            ->findOrFail($classeId)
            ->seances()
            ->with(['cours.professeur', 'salle'])
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();
    }
}