<?php
// app/Repositories/Interfaces/IExamenRepository.php
namespace App\Repositories\Interfaces;

interface IExamenRepository extends IBaseRepository
{
    public function getExamensByModule(int $moduleId);
    public function getExamensByPeriod(string $startDate, string $endDate);
    public function getExamensByClass(int $classeId);
    public function planifyExamen(array $examenData);
}