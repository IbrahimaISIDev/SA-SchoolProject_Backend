<?php
// app/Repositories/Interfaces/IClasseRepository.php
namespace App\Repositories\Interfaces;

interface IClasseRepository extends IBaseRepository
{
    public function getClassesByFiliere(int $filiereId);
    public function getClassesByNiveau(int $niveauId);
    public function getClassWithStudents(int $classeId);
    public function getClassSchedule(int $classeId);
}