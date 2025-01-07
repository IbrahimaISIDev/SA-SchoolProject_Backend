<?php
// app/Repositories/Evaluation/BulletinRepository.php
namespace App\Repositories\Evaluation;

use App\Models\Evaluation\Bulletin;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IBulletinRepository;

class BulletinRepository extends BaseRepository implements IBulletinRepository
{
    public function __construct(Bulletin $model)
    {
        parent::__construct($model);
    }

    public function generateBulletin(int $studentId, int $semestreId)
    {
        // Récupérer toutes les notes de l'étudiant pour le semestre
        $notes = $this->model->join('notes', 'notes.etudiant_id', '=', $studentId)
            ->where('semestre_id', $semestreId)
            ->with(['module', 'evaluation'])
            ->get();

        // Calculer les moyennes par module
        $moyennesParModule = $notes->groupBy('module_id')
            ->map(function ($moduleNotes) {
                return $moduleNotes->average('valeur');
            });

        // Créer le bulletin
        return $this->create([
            'etudiant_id' => $studentId,
            'semestre_id' => $semestreId,
            'moyenne_generale' => $moyennesParModule->average(),
            'date_generation' => now(),
            'moyennes' => $moyennesParModule->toJson()
        ]);
    }

    public function getBulletinsByClasse(int $classeId, int $semestreId)
    {
        return $this->model->whereHas('etudiant', function ($query) use ($classeId) {
            $query->where('classe_id', $classeId);
        })->where('semestre_id', $semestreId)
          ->with(['etudiant', 'semestre'])
          ->get();
    }

    public function calculateMoyenne(int $studentId, int $semestreId)
    {
        return $this->model->where('etudiant_id', $studentId)
            ->where('semestre_id', $semestreId)
            ->value('moyenne_generale');
    }

    public function getBulletinHistory(int $studentId)
    {
        return $this->model->where('etudiant_id', $studentId)
            ->with(['semestre'])
            ->orderBy('date_generation', 'desc')
            ->get();
    }
}