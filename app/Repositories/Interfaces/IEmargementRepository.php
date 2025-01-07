<?php
// app/Repositories/Interfaces/IEmargementRepository.php
namespace App\Repositories\Interfaces;

interface IEmargementRepository extends IBaseRepository
{
    public function create(array $data);
    public function marquerPresence(int $etudiantId, int $seanceId);
    public function validerEmargements(int $seanceId);
    public function getListeEmargement(int $seanceId);
    public function verifierDelaiEmargement(int $seanceId);
    public function validerParSeance(int $seanceId);

}