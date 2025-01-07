<?php

// app/Repositories/Evaluation/NoteRepository.php
namespace App\Repositories\Evaluation;

use App\Models\Evaluation\Note;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\INoteRepository;

class NoteRepository extends BaseRepository implements INoteRepository
{
    public function __construct(Note $model)
    {
        parent::__construct($model);
    }

    public function saisirNotes(array $notes)
    {
        $notesInserted = [];
        foreach ($notes as $note) {
            $notesInserted[] = $this->create([
                'etudiant_id' => $note['etudiant_id'],
                'evaluation_id' => $note['evaluation_id'],
                'valeur' => $note['valeur'],
                'observation' => $note['observation'] ?? null,
                'date_saisie' => now(),
                'professeur_id' => auth()->id()
            ]);
        }
        return $notesInserted;
    }

    public function calculerMoyenne(int $etudiantId, int $moduleId)
    {
        $notes = $this->model->where('etudiant_id', $etudiantId)
            ->whereHas('evaluation', function ($query) use ($moduleId) {
                $query->where('module_id', $moduleId);
            })->get();

        if ($notes->isEmpty()) {
            return 0;
        }

        // Calcul de la moyenne pondérée
        $totalCoef = $notes->sum('evaluation.coefficient');
        $totalPoints = $notes->sum(function ($note) {
            return $note->valeur * $note->evaluation->coefficient;
        });

        return $totalCoef > 0 ? $totalPoints / $totalCoef : 0;
    }

    public function genererBulletin(int $etudiantId, int $semestreId)
    {
        // Récupérer toutes les notes de l'étudiant pour le semestre
        $notes = $this->model->where('etudiant_id', $etudiantId)
            ->whereHas('evaluation', function ($query) use ($semestreId) {
                $query->where('semestre_id', $semestreId);
            })->with(['evaluation.module'])
            ->get();

        // Grouper par module et calculer les moyennes
        $moyennesParModule = $notes->groupBy('evaluation.module_id')
            ->map(function ($moduleNotes) {
                return [
                    'module' => $moduleNotes->first()->evaluation->module->libelle,
                    'moyenne' => $this->calculerMoyenne(
                        $moduleNotes->first()->etudiant_id,
                        $moduleNotes->first()->evaluation->module_id
                    )
                ];
            });

        return $moyennesParModule;
    }

    public function getNotesEtudiant(int $etudiantId, int $moduleId)
    {
        return $this->model->where('etudiant_id', $etudiantId)
            ->whereHas('evaluation', function ($query) use ($moduleId) {
                $query->where('module_id', $moduleId);
            })->with(['evaluation', 'professeur'])
            ->orderBy('date_saisie', 'desc')
            ->get();
    }

    public function getNotesByEtudiant(int $etudiantId, int $semestreId)
    {
        return $this->model
            ->where('etudiant_id', $etudiantId)
            ->where('semestre_id', $semestreId)
            ->get();
    }
}
