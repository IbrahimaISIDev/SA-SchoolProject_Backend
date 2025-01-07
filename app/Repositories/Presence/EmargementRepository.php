<?php
// app/Repositories/Presence/EmargementRepository.php
namespace App\Repositories\Presence;

use App\Models\Presence\Emargement;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IEmargementRepository;
use Carbon\Carbon;

class EmargementRepository extends BaseRepository implements IEmargementRepository
{
    public function __construct(Emargement $model)
    {
        parent::__construct($model);
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function marquerPresence(int $etudiantId, int $seanceId)
    {
        return $this->model->create([
            'etudiant_id' => $etudiantId,
            'seance_id' => $seanceId,
            'heure_emargement' => Carbon::now(),
            'statut' => 'EN_ATTENTE'
        ]);
    }

    public function validerEmargements(int $seanceId)
    {
        return $this->model->where('seance_id', $seanceId)
            ->where('statut', 'EN_ATTENTE')
            ->update(['statut' => 'VALIDE']);
    }

    public function getListeEmargement(int $seanceId)
    {
        return $this->model->with(['etudiant', 'seance'])
            ->where('seance_id', $seanceId)
            ->orderBy('heure_emargement')
            ->get();
    }

    public function verifierDelaiEmargement(int $seanceId)
    {
        $seance = $this->model->find($seanceId)->seance;
        $heureDebut = Carbon::parse($seance->heure_debut);
        $maintenant = Carbon::now();

        return $maintenant->diffInMinutes($heureDebut) <= 30;
    }

    // public function validerParSeance(int $seanceId)
    // {
    //     return $this->model->where('seance_id', $seanceId)
    //         ->update(['statut' => 'VALIDE']);
    // }

    public function validerParSeance(int $seanceId)
    {
        return $this->validerEmargements($seanceId); // Alias vers la méthode existante
    }
}
