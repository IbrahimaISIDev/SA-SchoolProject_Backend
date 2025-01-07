<?php
// app/Repositories/Interfaces/IDisponibiliteRepository.php
namespace App\Repositories\Interfaces;

interface IDisponibiliteRepository extends IBaseRepository
{
    public function getProfessorAvailability(int $professorId, string $date);
    public function checkAvailability(int $professorId, string $date, string $startTime, string $endTime);
    public function updateDisponibilite(int $professorId, array $disponibilites);
}