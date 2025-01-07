<?php
// app/Repositories/Interfaces/IProfesseurRepository.php
namespace App\Repositories\Interfaces;

interface IProfesseurRepository extends IBaseRepository
{
    public function getProfesseursDisponibles(string $date, string $heureDebut, string $heureFin);
    public function getHeuresEffectueesParMois(int $professeurId, string $mois);
    public function getDisponibilites(int $professeurId);
}