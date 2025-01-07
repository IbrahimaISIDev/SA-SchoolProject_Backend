<?php
// app/Repositories/Interfaces/INoteRepository.php
namespace App\Repositories\Interfaces;

interface INoteRepository extends IBaseRepository
{
    public function saisirNotes(array $notes);
    public function calculerMoyenne(int $etudiantId, int $moduleId);
    public function genererBulletin(int $etudiantId, int $semestreId);
    public function getNotesEtudiant(int $etudiantId, int $moduleId);
    public function getNotesByEtudiant(int $etudiantId, int $semestreId);

}
