<?php
// app/Repositories/Planification/DisponibiliteRepository.php
namespace App\Repositories\Planification;

use App\Models\Planification\Disponibilite;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IDisponibiliteRepository;
use Carbon\Carbon;

class DisponibiliteRepository extends BaseRepository implements IDisponibiliteRepository
{
    public function __construct(Disponibilite $model)
    {
        parent::__construct($model);
    }

    public function getProfessorAvailability(int $professorId, string $date)
    {
        return $this->model
            ->where('professeur_id', $professorId)
            ->whereDate('date', $date)
            ->get();
    }

    public function checkAvailability(int $professorId, string $date, string $startTime, string $endTime)
    {
        $start = Carbon::parse($date . ' ' . $startTime);
        $end = Carbon::parse($date . ' ' . $endTime);

        return $this->model
            ->where('professeur_id', $professorId)
            ->whereDate('date', $date)
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<=', $start)
                      ->where('heure_fin', '>', $start);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<', $end)
                      ->where('heure_fin', '>=', $end);
                });
            })
            ->doesntExist();
    }

    public function updateDisponibilite(int $professorId, array $disponibilites)
    {
        return $this->model
            ->where('professeur_id', $professorId)
            ->delete()
            ->create($disponibilites);
    }
}