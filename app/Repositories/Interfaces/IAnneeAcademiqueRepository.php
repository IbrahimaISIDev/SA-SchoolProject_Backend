<?php
// app/Repositories/Interfaces/IAnneeAcademiqueRepository.php
namespace App\Repositories\Interfaces;

interface IAnneeAcademiqueRepository extends IBaseRepository
{
    public function getCurrentAnneeAcademique();
    public function getAnneeAcademiqueActive();
    public function planifierClasses(int $anneeId, array $classesIds);
}