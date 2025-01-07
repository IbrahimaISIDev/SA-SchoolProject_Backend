<?php

// app/Repositories/Planification/SalleRepository.php
namespace App\Repositories\Planification;

use App\Models\Planification\Salle;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\ISalleRepository;
use Carbon\Carbon;

class SalleRepository extends BaseRepository implements ISalleRepository
{
    public function __construct(Salle $model)
    {
        parent::__construct($model);
    }

    public function getAvailableRooms(string $date, string $startTime, string $endTime)
    {
        $occupiedRoomIds = $this->getOccupiedRoomIds($date, $startTime, $endTime);

        return $this->model
            ->whereNotIn('id', $occupiedRoomIds)
            ->get();
    }

    public function getRoomCapacity(int $salleId)
    {
        return $this->model
            ->where('id', $salleId)
            ->value('nombre_places');
    }

    public function checkRoomAvailability(int $salleId, string $date, string $startTime, string $endTime)
    {
        $start = Carbon::parse($date . ' ' . $startTime);
        $end = Carbon::parse($date . ' ' . $endTime);

        return !$this->model->find($salleId)
            ->seances()
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<=', $start)
                      ->where('heure_fin', '>', $start);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<', $end)
                      ->where('heure_fin', '>=', $end);
                });
            })
            ->exists();
    }

    private function getOccupiedRoomIds(string $date, string $startTime, string $endTime)
    {
        $start = Carbon::parse($date . ' ' . $startTime);
        $end = Carbon::parse($date . ' ' . $endTime);

        return $this->model
            ->whereHas('seances', function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<=', $start)
                      ->where('heure_fin', '>', $start);
                })->orWhere(function ($q) use ($start, $end) {
                    $q->where('heure_debut', '<', $end)
                      ->where('heure_fin', '>=', $end);
                });
            })
            ->pluck('id');
    }
}