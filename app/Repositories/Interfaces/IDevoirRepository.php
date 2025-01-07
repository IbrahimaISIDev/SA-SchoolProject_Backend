<?php
// app/Repositories/Interfaces/IDevoirRepository.php
namespace App\Repositories\Interfaces;

interface IDevoirRepository extends IBaseRepository
{
    public function getDevoirsByModule(int $moduleId);
    public function getDevoirsByClass(int $classeId);
    public function getDevoirsByPeriod(string $startDate, string $endDate);
    public function assignDevoir(int $classeId, array $devoirData);
}