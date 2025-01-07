<?php

// app/Repositories/Academique/NiveauRepository.php
namespace App\Repositories\Academique;

use App\Models\Academique\Niveau;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\INiveauRepository;

class NiveauRepository extends BaseRepository implements INiveauRepository 
{
    public function __construct(Niveau $model)
    {
        parent::__construct($model);
    }

    public function getNiveauxByFiliere(int $filiereId)
    {
        return $this->model
            ->whereHas('filieres', function($query) use ($filiereId) {
                $query->where('filiere_id', $filiereId);
            })
            ->with('classes')
            ->get();
    }

    public function getClassesByNiveau(int $niveauId)
    {
        return $this->model
            ->with(['classes' => function($query) {
                $query->where('statut', 'active')
                      ->with(['etudiants', 'cours']);
            }])
            ->findOrFail($niveauId)
            ->classes;
    }

    public function getActiveNiveaux()
    {
        return $this->model
            ->where('statut', 'active')
            ->with(['filieres', 'classes'])
            ->get();
    }

    public function getEtudiantsByNiveau(int $niveauId)
    {
        return $this->model
            ->with(['classes.etudiants' => function($query) {
                $query->where('statut', 'active')
                      ->with(['absences', 'notes']);
            }])
            ->findOrFail($niveauId)
            ->classes
            ->pluck('etudiants')
            ->flatten();
    }
}