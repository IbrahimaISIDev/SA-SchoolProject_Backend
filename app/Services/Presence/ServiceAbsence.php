<?php
namespace App\Services;

use App\Repositories\Interfaces\IAbsenceRepository;
use App\Services\Interfaces\IServiceAnneeAcademique;
use App\Models\Utilisateurs\Etudiant;
use App\Models\Academique\Semestre;

class ServiceAbsence implements IServiceAbsence {
    private $absenceRepository;
    private $notificationService;
    
    public function __construct(
        IAbsenceRepository $absenceRepository,
        ServiceNotification $notificationService
    ) {
        $this->absenceRepository = $absenceRepository;
        $this->notificationService = $notificationService;
    }

    public function calculerHeuresAbsence(Etudiant $etudiant, ?Semestre $semestre = null): float {
        $absences = $semestre 
            ? $this->absenceRepository->getAbsencesSemestre($etudiant, $semestre)
            : $this->absenceRepository->getAllAbsences($etudiant);

        return $absences->sum('nombre_heures');
    }

    public function verifierSeuilAbsence(Etudiant $etudiant): void {
        $heuresAbsence = $this->calculerHeuresAbsence($etudiant);
        
        if ($heuresAbsence >= 20) {
            $this->notificationService->envoyerConvocation($etudiant);
        } elseif ($heuresAbsence >= 10) {
            $this->notificationService->envoyerAvertissement($etudiant);
        }
    }

    public function traiterJustification(Justification $justification): bool {
        // Vérifier si l'absence n'a pas déjà été justifiée
        if ($this->absenceRepository->estDejaJustifiee($justification->absence_id)) {
            throw new AbsenceException("Cette absence a déjà été justifiée.");
        }

        // Mettre à jour le statut de l'absence si la justification est acceptée
        if ($justification->statut === StatutJustification::ACCEPTEE) {
            $this->absenceRepository->exclureDesSeuils($justification->absence_id);
        }

        return true;
    }
}