<?php
// app/Repositories/Interfaces/IBulletinRepository.php
namespace App\Repositories\Interfaces;

interface IBulletinRepository extends IBaseRepository
{
    public function generateBulletin(int $studentId, int $semestreId);
    public function getBulletinsByClasse(int $classeId, int $semestreId);
    public function calculateMoyenne(int $studentId, int $semestreId);
    public function getBulletinHistory(int $studentId);
}