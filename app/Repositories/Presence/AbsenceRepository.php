<?php
// app/Repositories/Presence/AbsenceRepository.php
namespace App\Repositories\Presence;

use App\Models\Presence\Absence;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IAbsenceRepository;

class AbsenceRepository extends BaseRepository implements IAbsenceRepository
{
    public function __construct(Absence $model)
    {
        parent::__construct($model);
    }

    public function getAbsencesEtudiant(int $etudiantId)
    {
        return $this->model->with(['seance', 'justification'])
            ->where('etudiant_id', $etudiantId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getHeuresAbsences(int $etudiantId)
    {
        return $this->model->where('etudiant_id', $etudiantId)
            ->whereDoesntHave('justification', function ($query) {
                $query->where('statut', 'ACCEPTE');
            })
            ->join('seances', 'absences.seance_id', '=', 'seances.id')
            ->sum('seances.duree');
    }

    public function verifierSeuilAbsences(int $etudiantId)
    {
        $heuresAbsences = $this->getHeuresAbsences($etudiantId);

        if ($heuresAbsences >= 20) {
            return 'CONVOCATION';
        } elseif ($heuresAbsences >= 10) {
            return 'AVERTISSEMENT';
        }

        return 'NORMAL';
    }

    public function justifierAbsence(int $absenceId, array $data)
    {
        $absence = $this->find($absenceId);
        return $absence->justification()->create([
            'motif' => $data['motif'],
            'piece_jointe' => $data['piece_jointe'] ?? null,
            'date_soumission' => now(),
            'statut' => 'EN_ATTENTE'
        ]);
    }

    public function traiterJustification(int $justificationId, bool $accepte)
    {
        return $this->model->whereHas('justification', function ($query) use ($justificationId) {
            $query->where('id', $justificationId);
        })->update([
            'justifie' => $accepte
        ]);
    }

    public function getHeuresAbsence(int $etudiantId, int $semestreId)
    {
        return $this->model
            ->where('etudiant_id', $etudiantId)
            ->where('semestre_id', $semestreId)
            ->sum('duree');
    }

    public function genererRapport(array $criteres)
    {
        return $this->model
            ->where('date_absence', '>=', $criteres['date_debut'])
            ->where('date_absence', '<=', $criteres['date_fin'])
            ->where('etudiant_id', $criteres['etudiant_id'])
            ->get();
    }
}
