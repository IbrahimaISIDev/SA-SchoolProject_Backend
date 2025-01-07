<?php
namespace App\Services;

use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repositories\Interfaces\ISeanceRepository;
use App\Repositories\Interfaces\ICoursRepository;

// app/Services/Utilisateurs/ServiceProfesseur.php
class ServiceProfesseur extends ServiceUtilisateur {
    protected $coursRepository;
    protected $seanceRepository;

    public function __construct(
        IUtilisateurRepository $utilisateurRepository,
        ICoursRepository $coursRepository,
        ISeanceRepository $seanceRepository
    ) {
        parent::__construct($utilisateurRepository);
        $this->coursRepository = $coursRepository;
        $this->seanceRepository = $seanceRepository;
    }

    public function recupererCours(int $professeurId) {
        return $this->coursRepository->getByProfesseur($professeurId);
    }

    public function calculerHeuresEffectuees(int $professeurId, int $moisId) {
        return $this->seanceRepository->getHeuresEffectueesParMois($professeurId, $moisId);
    }

    public function demanderAnnulationSeance(int $seanceId, string $motif) {
        $seance = $this->seanceRepository->find($seanceId);
        // Vérification des autorisations
        return $this->seanceRepository->demanderAnnulation($seanceId, $motif);
    }
}