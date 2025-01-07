<?php
// app/Services/Utilisateurs/ServiceEtudiant.php
namespace App\Services;

use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repositories\Interfaces\IAbsenceRepository;
use App\Repositories\Interfaces\INoteRepository;
use App\Repositories\Interfaces\IEmargementRepository;
use App\Repositories\Interfaces\ISeanceRepository;
use App\Services\Exceptions\DelaiEmargementDepasseException;

class ServiceEtudiant extends ServiceUtilisateur
{
    protected $absenceRepository;
    protected $noteRepository;
    protected $seanceRepository;
    protected $emargementRepository;

    public function __construct(
        IUtilisateurRepository $utilisateurRepository,
        IAbsenceRepository $absenceRepository,
        INoteRepository $noteRepository,
        ISeanceRepository $seanceRepository,
        IEmargementRepository $emargementRepository
    ) {
        parent::__construct($utilisateurRepository);
        $this->absenceRepository = $absenceRepository;
        $this->noteRepository = $noteRepository;
        $this->seanceRepository = $seanceRepository;
        $this->emargementRepository = $emargementRepository;
    }

    public function calculerHeuresAbsence(int $etudiantId, int $semestreId)
    {
        return $this->absenceRepository->getHeuresAbsence($etudiantId, $semestreId);
    }

    public function recupererNotes(int $etudiantId, int $semestreId)
    {
        return $this->noteRepository->getNotesByEtudiant($etudiantId, $semestreId);
    }

    public function marquerPresence(int $etudiantId, int $seanceId)
    {
        // Vérification du délai de 30 minutes
        $seance = $this->seanceRepository->find($seanceId);
        if (!$this->verifierDelaiEmargement($seance)) {
            throw new DelaiEmargementDepasseException();
        }

        return $this->emargementRepository->create([
            'etudiant_id' => $etudiantId,
            'seance_id' => $seanceId,
            'date_emargement' => now()
        ]);
    }

    protected function verifierDelaiEmargement($seance): bool
    {
        $heureDebut = $seance->heure_debut;
        $heureLimite = \Carbon\Carbon::parse($heureDebut)->addMinutes(30);
        return now()->lessThanOrEqualTo($heureLimite);
    }
}
