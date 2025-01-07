<?php
namespace App\Services\Evaluation;

use App\Models\Evaluation\Note;
use App\Models\Evaluation\Devoir;
use App\Models\Evaluation\Examen;
use App\Models\Utilisateurs\Etudiant;
use App\Repositories\Interfaces\INoteRepository;
use Illuminate\Database\Eloquent\Collection;

class ServiceNote implements INoteService 
{
    private $noteRepository;

    public function __construct(INoteRepository $noteRepository) 
    {
        $this->noteRepository = $noteRepository;
    }

    public function saisir(array $data): Note 
    {
        if (!isset($data['valeur']) || $data['valeur'] < 0 || $data['valeur'] > 20) {
            throw new \Exception('La note doit être comprise entre 0 et 20');
        }

        $notes = $this->noteRepository->saisirNotes([
            [
                'etudiant_id' => $data['etudiant_id'],
                'evaluation_id' => $data['evaluation_id'],
                'valeur' => $data['valeur'],
                'observation' => $data['observation'] ?? null
            ]
        ]);

        return $notes[0];
    }

    public function modifier(int $noteId, float $valeur): Note 
    {
        if ($valeur < 0 || $valeur > 20) {
            throw new \Exception('La note doit être comprise entre 0 et 20');
        }

        return $this->noteRepository->update($noteId, ['valeur' => $valeur]);
    }

    public function supprimer(int $noteId): bool 
    {
        return $this->noteRepository->delete($noteId);
    }

    public function calculerMoyenne(int $etudiantId, int $moduleId): float 
    {
        return $this->noteRepository->calculerMoyenne($etudiantId, $moduleId);
    }

    public function recupererNotesEtudiant(int $etudiantId, int $semestreId): Collection 
    {
        return $this->noteRepository->getNotesByEtudiant($etudiantId, $semestreId);
    }

    public function verifierValidation(int $etudiantId, int $moduleId): bool 
    {
        $moyenne = $this->calculerMoyenne($etudiantId, $moduleId);
        return $moyenne >= 10;
    }

    public function enregistrerNoteDevoir(Etudiant $etudiant, Devoir $devoir, float $note): bool 
    {
        if ($note < 0 || $note > 20) {
            throw new \Exception('La note doit être comprise entre 0 et 20');
        }

        $notes = $this->noteRepository->saisirNotes([
            [
                'etudiant_id' => $etudiant->id,
                'evaluation_id' => $devoir->id,
                'valeur' => $note,
            ]
        ]);

        return !empty($notes);
    }

    public function enregistrerNoteExamen(Etudiant $etudiant, Examen $examen, float $note): bool 
    {
        if ($note < 0 || $note > 20) {
            throw new \Exception('La note doit être comprise entre 0 et 20');
        }

        $notes = $this->noteRepository->saisirNotes([
            [
                'etudiant_id' => $etudiant->id,
                'evaluation_id' => $examen->id,
                'valeur' => $note,
            ]
        ]);

        return !empty($notes);
    }

    public function calculerMoyenneModule(Etudiant $etudiant, int $moduleId): float 
    {
        return $this->calculerMoyenne($etudiant->id, $moduleId);
    }

    public function genererBulletin(Etudiant $etudiant, int $semestreId): array 
    {
        $moyennesParModule = $this->noteRepository->genererBulletin($etudiant->id, $semestreId);
        
        return [
            'etudiant' => [
                'id' => $etudiant->id,
                'nom' => $etudiant->nom,
                'prenom' => $etudiant->prenom
            ],
            'semestre' => $semestreId,
            'modules' => $moyennesParModule->toArray(),
            'moyenne_generale' => $moyennesParModule->avg('moyenne'),
            'validation_semestre' => $moyennesParModule->avg('moyenne') >= 10
        ];
    }

    public function verifierSaisieComplete(int $evaluationId): bool 
    {
        // Get all notes for this evaluation
        $notes = $this->noteRepository->model->where('evaluation_id', $evaluationId)->count();
        
        // Get expected number of students from the evaluation
        $evaluation = $this->noteRepository->model
            ->where('id', $evaluationId)
            ->with('evaluation.groupe.etudiants')
            ->first();
            
        if (!$evaluation) {
            return false;
        }
        
        $expectedStudents = $evaluation->evaluation->groupe->etudiants->count();
        
        return $notes === $expectedStudents;
    }
}