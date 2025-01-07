<?php
namespace App\Services;

use App\Services\Exceptions\ConflitPlanificationException;
use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Repositories\Interfaces\IAnneeRepository;
use App\Repositories\Interfaces\ICoursRepository;
use App\Repositories\Interfaces\IAbsenceRepository;

use App\Services\Interfaces\IServiceSemestre;
use App\Models\Academique\Semestre;

use App\Services\Interfaces\IServiceUtilisateur;
use App\Models\Utilisateurs\Utilisateur;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

// app/Services/Utilisateurs/ServiceResponsable.php
class ServiceResponsable extends ServiceUtilisateur {
    protected $anneeRepository;
    protected $coursRepository;
    protected $absenceRepository;

    public function __construct(
        IUtilisateurRepository $utilisateurRepository,
        IAnneeRepository $anneeRepository,
        ICoursRepository $coursRepository,
        IAbsenceRepository $absenceRepository // Ajouter la dépendance

    ) {
        parent::__construct($utilisateurRepository);
        $this->anneeRepository = $anneeRepository;
        $this->coursRepository = $coursRepository;
        $this->absenceRepository = $absenceRepository; // Initialisation

    }

    public function creerAnneeAcademique(array $data) {
        return $this->anneeRepository->create($data);
    }

    public function planifierCours(array $data) {
        // Vérification des conflits
        if ($this->coursRepository->verifierConflits($data)) {
            throw new ConflitPlanificationException();
        }
        return $this->coursRepository->create($data);
    }

    public function genererRapportAbsences(array $criteres) {
        return $this->absenceRepository->genererRapport($criteres);
    }
}