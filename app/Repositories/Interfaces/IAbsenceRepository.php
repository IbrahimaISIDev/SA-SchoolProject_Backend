<?php
// app/Repositories/Interfaces/IAbsenceRepository.php
namespace App\Repositories\Interfaces;

interface IAbsenceRepository extends IBaseRepository
{
    public function getAbsencesEtudiant(int $etudiantId);
    public function getHeuresAbsences(int $etudiantId);
    public function verifierSeuilAbsences(int $etudiantId);
    public function justifierAbsence(int $absenceId, array $data);
    public function traiterJustification(int $justificationId, bool $accepte);
    public function getHeuresAbsence(int $etudiantId, int $semestreId);
    public function genererRapport(array $criteres);
}