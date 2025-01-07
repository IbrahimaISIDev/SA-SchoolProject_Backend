<?php

// app/Repositories/Academique/ModuleRepository.php
namespace App\Repositories\Academique;

use App\Models\Academique\Module;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IModuleRepository;

class ModuleRepository extends BaseRepository implements IModuleRepository 
{
    public function __construct(Module $model)
    {
        parent::__construct($model);
    }

    public function getModulesByFiliere(int $filiereId)
    {
        return $this->model
            ->whereHas('filieres', function($query) use ($filiereId) {
                $query->where('filiere_id', $filiereId);
            })
            ->with('cours')
            ->get();
    }

    public function getModulesBySemestre(int $semestreId)
    {
        return $this->model
            ->whereHas('cours', function($query) use ($semestreId) {
                $query->where('semestre_id', $semestreId);
            })
            ->with(['cours', 'professeurs'])
            ->get();
    }

    public function getModulesForProfesseur(int $professeurId)
    {
        return $this->model
            ->whereHas('professeurs', function($query) use ($professeurId) {
                $query->where('professeur_id', $professeurId);
            })
            ->with('cours')
            ->get();
    }

    public function getCoursForModule(int $moduleId)
    {
        return $this->model
            ->with(['cours' => function($query) {
                $query->with(['professeur', 'classes', 'seances']);
            }])
            ->findOrFail($moduleId)
            ->cours;
    }
}