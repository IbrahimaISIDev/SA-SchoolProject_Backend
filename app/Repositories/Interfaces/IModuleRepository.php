<?php
namespace App\Repositories\Interfaces;

interface IModuleRepository extends IBaseRepository
{
    public function getModulesByFiliere(int $filiereId);
    public function getModulesBySemestre(int $semestreId);
    public function getModulesForProfesseur(int $professeurId);
    public function getCoursForModule(int $moduleId);
}