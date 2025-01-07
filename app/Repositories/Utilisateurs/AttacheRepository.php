<?php

namespace App\Repositories;

use App\Models\Utilisateurs\Attache;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IAttacheRepository;

class AttacheRepository extends BaseRepository implements IAttacheRepository
{
    /**
     * @var Attache
     */
    protected $model;

    /**
     * AttacheRepository constructor.
     *
     * @param Attache $attache
     */
    public function __construct(Attache $attache)
    {
        parent::__construct($attache);
        $this->model = $attache;
    }

    /**
     * Valider une présence par l'attaché
     *
     * @param int $presenceId
     * @return bool
     */
    public function validatePresence(int $presenceId): bool
    {
        try {
            $presence = \App\Models\Presence\Emargement::findOrFail($presenceId);
            $presence->update([
                'validee_par' => auth()->id(),
                'date_validation' => now(),
                'statut' => 'VALIDEE'
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Valider une justification d'absence
     *
     * @param int $justificationId
     * @return bool
     */
    public function validateJustification(int $justificationId): bool
    {
        try {
            $justification = \App\Models\Presence\Justification::findOrFail($justificationId);
            $justification->update([
                'validee_par' => auth()->id(),
                'date_validation' => now(),
                'statut' => 'ACCEPTEE'
            ]);
            
            // Mettre à jour le statut de l'absence associée
            if ($justification->absence) {
                $justification->absence->update([
                    'justifiee' => true
                ]);
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Récupérer les attachés par département
     *
     * @param int $departementId
     * @return Collection
     */
    public function getAttachesByDepartement(int $departementId)
    {
        return $this->model
            ->where('departement_id', $departementId)
            ->where('statut', 'ACTIF')
            ->get();
    }
}