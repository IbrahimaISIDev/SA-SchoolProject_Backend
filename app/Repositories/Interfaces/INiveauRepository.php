<?php
namespace App\Repositories\Interfaces;

interface INiveauRepository extends IBaseRepository
{
    public function getNiveauxByFiliere(int $filiereId);
    public function getClassesByNiveau(int $niveauId);
    public function getActiveNiveaux();
    public function getEtudiantsByNiveau(int $niveauId);
}