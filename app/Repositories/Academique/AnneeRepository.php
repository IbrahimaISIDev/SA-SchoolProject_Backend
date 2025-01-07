<?php
// app/Repositories/Academique/AnneeRepository.php
namespace App\Repositories\Academique;

use App\Models\Academique\AnneeAcademique;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IAnneeAcademiqueRepository;

class AnneeRepository extends BaseRepository implements IAnneeAcademiqueRepository 
{
    public function __construct(AnneeAcademique $model)
    {
        parent::__construct($model);
    }

    public function getCurrentAnneeAcademique()
    {
        return $this->model
            ->where('statut', 'active')
            ->orderBy('date_debut', 'desc')
            ->first();
    }

    public function getAnneeAcademiqueActive()
    {
        return $this->model
            ->where('statut', 'active')
            ->with(['semestres', 'classes'])
            ->first();
    }

    public function planifierClasses(int $anneeId, array $classesIds)
    {
        $annee = $this->findById($anneeId);
        return $annee->classes()->sync($classesIds);
    }
}