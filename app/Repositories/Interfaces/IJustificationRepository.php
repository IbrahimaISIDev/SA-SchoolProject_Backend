<?php
// app/Repositories/Interfaces/IJustificationRepository.php
namespace App\Repositories\Interfaces;

interface IJustificationRepository extends IBaseRepository
{
    public function getPendingJustifications();
    public function getJustificationsByStudent(int $studentId);
    public function getJustificationsByPeriod(string $startDate, string $endDate);
    public function updateStatus(int $justificationId, string $status);
    public function traiter(int $justificationId, string $decision, string $commentaire = null);
}