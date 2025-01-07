<?php
// app/Repositories/Interfaces/IAttacheRepository.php
namespace App\Repositories\Interfaces;

interface IAttacheRepository extends IBaseRepository
{
    public function validatePresence(int $presenceId);
    public function validateJustification(int $justificationId);
    public function getAttachesByDepartement(int $departementId);
}