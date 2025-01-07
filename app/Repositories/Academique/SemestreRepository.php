<?php

// app/Repositories/Academique/SemestreRepository.php
namespace App\Repositories\Academique;

use App\Models\Academique\Semestre;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ISemestreRepository;

class SemestreRepository extends BaseRepository implements ISemestreRepository 
{
    public function __construct(Semestre $model)
    {
        parent::__construct($model);
    }

    public function getCurrentSemestre()
    {
        return $this->model
            ->where('statut', 'en_cours')
            ->with(['modules', 'cours'])
            ->first();
    }

    public function getSemestresByAnnee(int $anneeId)
    {
        return $this->model
            ->where('annee_academique_id', $anneeId)
            ->with(['modules', 'cours'])
            ->orderBy('date_debut')
            ->get();
    }

    public function updateStatus(int $id, string $status)
    {
        $semestre = $this->findById($id);
        return $semestre->update(['statut' => $status]);
    }

    public function getModulesBySemestre(int $semestreId)
    {
        return $this->model
            ->with(['modules' => function($query) {
                $query->with(['cours', 'professeurs']);
            }])
            ->findOrFail($semestreId)
            ->modules;
    }

    public function getCoursForSemestre(int $semestreId)
    {
        return $this->model
            ->with(['cours' => function($query) {
                $query->with(['professeur', 'classes', 'seances'])
                      ->where('statut', 'actif');
            }])
            ->findOrFail($semestreId)
            ->cours;
    }
}