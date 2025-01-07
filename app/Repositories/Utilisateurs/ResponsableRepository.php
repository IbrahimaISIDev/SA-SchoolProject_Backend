<?php

namespace App\Repositories\Utilisateurs;

use App\Models\Utilisateurs\Responsable;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IResponsableRepository;
use Illuminate\Database\Eloquent\Collection;

class ResponsableRepository extends BaseRepository implements IResponsableRepository
{
    /**
     * @var Responsable
     */
    protected $model;

    /**
     * ResponsableRepository constructor.
     *
     * @param Responsable $model
     */
    public function __construct(Responsable $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Récupère un responsable avec ses cours associés
     *
     * @param int $responsableId
     * @return Responsable|null
     */
    public function getResponsableWithCourses(int $responsableId)
    {
        return $this->model
            ->with(['cours' => function ($query) {
                $query->with(['seances', 'classe', 'module']);
            }])
            ->find($responsableId);
    }

    /**
     * Récupère tous les responsables d'une année académique
     *
     * @param int $anneeId
     * @return Collection
     */
    public function getResponsablesByAnneeAcademique(int $anneeId)
    {
        return $this->model
            ->whereHas('anneesAcademiques', function ($query) use ($anneeId) {
                $query->where('annee_academique_id', $anneeId);
            })
            ->with('anneesAcademiques')
            ->get();
    }

    /**
     * Valide une séance de cours
     *
     * @param int $sessionId
     * @return bool
     */
    public function validateCourseSession(int $sessionId): bool
    {
        try {
            $seance = $this->model->seances()->find($sessionId);
            if (!$seance) {
                return false;
            }

            $seance->update([
                'statut' => 'VALIDEE',
                'date_validation' => now(),
                'validateur_id' => auth()->id()
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la validation de la séance : ' . $e->getMessage());
            return false;
        }
    }
}