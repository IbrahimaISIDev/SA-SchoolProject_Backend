<?php
namespace App\Services;

use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repositories\Interfaces\IEmargementRepository;
use App\Repositories\Interfaces\IJustificationRepository;
use App\Repositories\Interfaces\ISeanceRepository; // Ajout de l'interface manquante

// app/Services/Utilisateurs/ServiceAttache.php
class ServiceAttache extends ServiceUtilisateur {
    protected $emargementRepository;
    protected $justificationRepository;
    protected $seanceRepository; // Ajout de la propriété manquante

    public function __construct(
        IUtilisateurRepository $utilisateurRepository,
        IEmargementRepository $emargementRepository,
        IJustificationRepository $justificationRepository,
        ISeanceRepository $seanceRepository // Injection du repository manquant
    ) {
        parent::__construct($utilisateurRepository);
        $this->emargementRepository = $emargementRepository;
        $this->justificationRepository = $justificationRepository;
        $this->seanceRepository = $seanceRepository; // Assignation
    }

    /**
     * Valider l'émargement pour une séance spécifique
     *
     * @param int $seanceId
     * @return bool
     */
    public function validerEmargement(int $seanceId) {
        return $this->emargementRepository->validerParSeance($seanceId);
    }

    /**
     * Traiter une justification
     *
     * @param int $justificationId
     * @param string $decision
     * @param string|null $commentaire
     * @return bool
     */
    public function traiterJustification(int $justificationId, string $decision, string $commentaire = null) {
        return $this->justificationRepository->traiter($justificationId, $decision, $commentaire);
    }

    /**
     * Valider une séance spécifique
     *
     * @param int $seanceId
     * @return bool
     */
    public function validerSeance(int $seanceId) {
        return $this->seanceRepository->valider($seanceId);
    }
}
