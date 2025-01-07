<?php
// app/Repositories/Presence/JustificationRepository.php
namespace App\Repositories\Presence;

use App\Models\Presence\Justification;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IJustificationRepository;

class JustificationRepository extends BaseRepository implements IJustificationRepository
{
    public function __construct(Justification $model)
    {
        parent::__construct($model);
    }

    public function getPendingJustifications()
    {
        return $this->model->with(['absence.etudiant', 'absence.seance'])
            ->where('statut', 'EN_ATTENTE')
            ->orderBy('date_soumission')
            ->get();
    }

    public function getJustificationsByStudent(int $studentId)
    {
        return $this->model->whereHas('absence', function ($query) use ($studentId) {
            $query->where('etudiant_id', $studentId);
        })->with(['absence.seance'])
            ->orderBy('date_soumission', 'desc')
            ->get();
    }

    public function getJustificationsByPeriod(string $startDate, string $endDate)
    {
        return $this->model->whereBetween('date_soumission', [$startDate, $endDate])
            ->with(['absence.etudiant', 'absence.seance'])
            ->orderBy('date_soumission')
            ->get();
    }

    public function updateStatus(int $justificationId, string $status)
    {
        $justification = $this->find($justificationId);
        $updated = $justification->update(['statut' => $status]);

        if ($updated && $status === 'ACCEPTE') {
            $justification->absence()->update(['justifie' => true]);
        }

        return $updated;
    }

    public function traiter(int $justificationId, string $decision, string $commentaire = null)
    {
        $justification = $this->find($justificationId);
        $updated = $justification->update([
            'statut' => $decision,
            'commentaire' => $commentaire
        ]);

        if ($updated && $decision === 'ACCEPTE') {
            $justification->absence()->update(['justifie' => true]);
        }

        return $updated;
    }
}
