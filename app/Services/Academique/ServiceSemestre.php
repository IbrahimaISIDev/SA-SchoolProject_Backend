<?php

namespace App\Services\Academique;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\ISemestreRepository;
use App\Services\Interfaces\IServiceSemestre;
use App\Models\Academique\Semestre;

class ServiceSemestre implements IServiceSemestre
{
    private $semestreRepository;

    public function __construct(ISemestreRepository $semestreRepository)
    {
        $this->semestreRepository = $semestreRepository;
    }

    public function creer(array $data): Semestre
    {
        if (!$this->verifierDatesValides($data)) {
            throw new \Exception("Les dates du semestre doivent être comprises dans l'année académique");
        }

        if ($this->semestreRepository->existeChevauchement($data)) {
            throw new \Exception("Les dates du semestre chevauchent un autre semestre");
        }

        return $this->semestreRepository->create($data);
    }

    public function modifier(int $id, array $data): Semestre
    {
        $semestre = $this->semestreRepository->find($id);

        // Vérifie explicitement si l'objet est null
        if ($semestre === null) {
            throw new \Exception("Semestre non trouvé");
        }

        if (!$this->verifierDatesValides($data)) {
            throw new \Exception("Les dates du semestre doivent être comprises dans l'année académique");
        }

        // Mise à jour et retour d'une instance Semestre
        return $this->semestreRepository->update($id, $data);
    }


    public function supprimer(int $id): bool
    {
        $semestre = $this->semestreRepository->find($id);

        if (!$semestre) {
            throw new \Exception("Semestre non trouvé");
        }

        // Vérifier si le semestre peut être supprimé
        if ($this->hasCours($id)) {
            throw new \Exception("Impossible de supprimer un semestre contenant des cours");
        }

        return $this->semestreRepository->delete($id);
    }

    public function recupererCours(int $semestreId): Collection
    {
        return $this->semestreRepository->getCours($semestreId);
    }

    public function verifierPeriode(int $semestreId, DateTime $date): bool
    {
        $semestre = $this->semestreRepository->find($semestreId);

        if (!$semestre) {
            throw new \Exception("Semestre non trouvé");
        }

        return $date >= $semestre->date_debut && $date <= $semestre->date_fin;
    }

    public function calculerProgression(int $semestreId): float
    {
        $semestre = $this->semestreRepository->find($semestreId);

        if (!$semestre) {
            throw new \Exception("Semestre non trouvé");
        }

        $totalDays = $semestre->date_debut->diffInDays($semestre->date_fin);
        $elapsedDays = $semestre->date_debut->diffInDays(now());

        if ($elapsedDays > $totalDays) {
            return 100.0;
        }

        return round(($elapsedDays / $totalDays) * 100, 2);
    }

    public function planifierModules(int $semestreId, array $modulesData): void
    {
        $semestre = $this->semestreRepository->find($semestreId);

        if (!$semestre) {
            throw new \Exception("Semestre non trouvé");
        }

        foreach ($modulesData as $moduleData) {
            if (!$this->verifierQuotaHoraireValide($moduleData)) {
                throw new \Exception("Quota horaire invalide pour le module " . $moduleData['libelle']);
            }
        }

        $this->semestreRepository->attachModules($semestreId, $modulesData);
    }

    public function getSemestreActif(int $anneeId): ?Semestre
    {
        return $this->semestreRepository->getSemestreActif($anneeId);
    }

    private function verifierDatesValides(array $data): bool
    {
        // Logique de vérification des dates
        return true; // À implémenter selon les règles exactes
    }

    private function verifierQuotaHoraireValide(array $moduleData): bool
    {
        return $moduleData['quota_horaire'] > 0 && $moduleData['quota_horaire'] <= 100;
    }

    private function hasCours(int $semestreId): bool
    {
        return $this->semestreRepository->getCours($semestreId)->isNotEmpty();
    }
}
