<?php
// app/Repositories/Academique/FiliereRepository.php
namespace App\Repositories\Academique;

use App\Models\Academique\Filiere;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IFiliereRepository;

class FiliereRepository extends BaseRepository implements IFiliereRepository 
{
    public function __construct(Filiere $model)
    {
        parent::__construct($model);
    }

    public function getFilieresByNiveau(int $niveauId)
    {
        return $this->model
            ->whereHas('niveaux', function($query) use ($niveauId) {
                $query->where('niveau_id', $niveauId);
            })
            ->get();
    }

    public function getFiliereWithClasses(int $filiereId)
    {
        return $this->model
            ->with(['classes' => function($query) {
                $query->where('statut', 'active');
            }])
            ->findOrFail($filiereId);
    }

    public function getActivesFilieres()
    {
        return $this->model
            ->where('statut', 'active')
            ->with(['niveaux', 'classes'])
            ->get();
    }
}