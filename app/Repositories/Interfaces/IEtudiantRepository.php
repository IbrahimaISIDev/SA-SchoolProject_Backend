<?php
// app/Repositories/Interfaces/IEtudiantRepository.php
namespace App\Repositories\Interfaces;

interface IEtudiantRepository extends IBaseRepository
{
    public function importerEtudiants(string $fichierExcel);
    public function getEtudiantsParClasse(int $classeId);
    public function changerClasse(int $etudiantId, int $classeId);
}