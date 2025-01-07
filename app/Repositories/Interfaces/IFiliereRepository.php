<?php
// app/Repositories/Interfaces/IFiliereRepository.php
namespace App\Repositories\Interfaces;

interface IFiliereRepository extends IBaseRepository
{
    public function getFilieresByNiveau(int $niveauId);
    public function getFiliereWithClasses(int $filiereId);
    public function getActivesFilieres();
}