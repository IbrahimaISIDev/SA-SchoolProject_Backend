<?php
// app/Repositories/Interfaces/ISeanceRepository.php
namespace App\Repositories\Interfaces;

use App\Models\Planification\Seance;

interface ISeanceRepository extends IBaseRepository
{
    public function planifierSeance(array $data);
    public function annulerSeance(int $seanceId, string $motif);
    public function validerSeance(int $seanceId);
    public function getSeancesParCours(int $coursId);
    public function verifierConflitsHoraires(array $data);
    public function valider(int $seanceId);
    public function getHeuresEffectueesParMois(int $professeurId, int $moisId); // Ajout méthode 1
    public function demanderAnnulation(int $seanceId, string $motif); // Ajout méthode 2
       /**
     * Trouve une séance par son ID
     */
    public function findById(int $id): ?Seance;
    
    /**
     * Crée une nouvelle séance
     */
    public function create(array $data): Seance;
    
    /**
     * Met à jour une séance
     */
    public function update(int $id, array $data): bool;
    
    /**
     * Vérifie le quota horaire restant pour un cours
     */
    public function verifierQuotaRestant(int $coursId, float $nombreHeures): bool;

}