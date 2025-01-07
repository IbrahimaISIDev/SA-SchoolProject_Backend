<?php
// app/Repositories/Interfaces/IResponsableRepository.php
namespace App\Repositories\Interfaces;

interface IResponsableRepository extends IBaseRepository
{
    public function getResponsableWithCourses(int $responsableId);
    public function getResponsablesByAnneeAcademique(int $anneeId);
    public function validateCourseSession(int $sessionId);
}