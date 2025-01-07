<?php

namespace App\Services\Academique;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IAnneeRepository;
use App\Services\Interfaces\IServiceAnneeAcademique;
use App\Models\Academique\AnneeAcademique;
use App\Models\Academique\Semestre;

class ServiceAnnee implements IServiceAnneeAcademique 
{
    private $anneeRepository;

    public function __construct(IAnneeRepository $anneeRepository) 
    {
        $this->anneeRepository = $anneeRepository;
    }

    public function creer(array $data): AnneeAcademique 
    {
        // Vérifier s'il existe une année en cours
        $anneeEnCours = $this->anneeRepository->getAnneeEnCours();
        if ($anneeEnCours) {
            throw new \Exception("Une année académique est déjà en cours");
        }

        // Valider les dates
        if ($data['date_fin'] <= $data['date_debut']) {
            throw new \Exception("La date de fin doit être supérieure à la date de début");
        }

        return $this->anneeRepository->create($data);
    }

    public function activer(int $anneeId): bool
    {
        $annee = $this->anneeRepository->find($anneeId);
        if (!$annee) {
            throw new \Exception("Année académique non trouvée");
        }

        // Vérifier qu'il n'y a pas déjà une année active
        $anneeActive = $this->recupererEnCours();
        if ($anneeActive && $anneeActive->id !== $anneeId) {
            throw new \Exception("Une autre année académique est déjà active");
        }

        return $this->anneeRepository->updateStatut($anneeId, 'EN_COURS');
    }

    public function cloturer(int $anneeId): bool
    {
        $annee = $this->anneeRepository->find($anneeId);
        if (!$annee) {
            throw new \Exception("Année académique non trouvée");
        }

        if ($annee->statut !== 'EN_COURS') {
            throw new \Exception("Seule une année en cours peut être clôturée");
        }

        return $this->anneeRepository->updateStatut($anneeId, 'CLOTUREE');
    }

    public function recupererEnCours(): ?AnneeAcademique
    {
        return $this->anneeRepository->getAnneeEnCours();
    }

    public function recupererSemestres(int $anneeId): Collection
    {
        $annee = $this->anneeRepository->find($anneeId);
        if (!$annee) {
            throw new \Exception("Année académique non trouvée");
        }

        return $annee->semestres;
    }

    public function ajouterSemestre(int $anneeId, array $data): Semestre
    {
        $annee = $this->anneeRepository->find($anneeId);
        if (!$annee) {
            throw new \Exception("Année académique non trouvée");
        }

        if ($this->verifierChevauchement($data)) {
            throw new \Exception("Les dates du semestre chevauchent un semestre existant");
        }

        return $this->anneeRepository->createSemestre($anneeId, $data);
    }

    public function verifierChevauchement(array $data): bool
    {
        return $this->anneeRepository->checkSemestreChevauchement(
            $data['annee_id'],
            $data['date_debut'],
            $data['date_fin']
        );
    }

    public function creerAnneeAcademique(string $libelle, Carbon $dateDebut, Carbon $dateFin): bool
    {
        return $this->creer([
            'libelle' => $libelle,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin
        ]) instanceof AnneeAcademique;
    }

    public function verifierAnneeEnCours(): bool
    {
        return $this->recupererEnCours() !== null;
    }

    public function getAnneeActive(): ?object
    {
        return $this->recupererEnCours();
    }

    public function cloturerAnnee(int $anneeId): bool
    {
        return $this->cloturer($anneeId);
    }

    public function listerAnnees(array $filtres = []): array
    {
        return $this->anneeRepository->getAllWithFilters($filtres)->toArray();
    }

    public function planifierClasses(int $anneeId, array $classesIds): void 
    {
        $annee = $this->anneeRepository->find($anneeId);
        
        if (!$annee) {
            throw new \Exception("Année académique non trouvée");
        }

        if ($annee->statut !== 'EN_COURS') {
            throw new \Exception("Impossible de planifier les classes pour une année non active");
        }

        $this->anneeRepository->attachClasses($anneeId, $classesIds);
    }

    public function terminer(int $anneeId): void 
    {
        $annee = $this->anneeRepository->find($anneeId);
        
        if (!$annee) {
            throw new \Exception("Année académique non trouvée");
        }

        // Vérifier si tous les cours sont terminés
        if (!$this->tousLesCoursTermines($anneeId)) {
            throw new \Exception("Impossible de terminer l'année : des cours sont encore en cours");
        }

        $this->anneeRepository->updateStatut($anneeId, 'TERMINEE');
    }

    private function tousLesCoursTermines(int $anneeId): bool 
    {
        return $this->anneeRepository->verifierCoursTermines($anneeId);
    }
}