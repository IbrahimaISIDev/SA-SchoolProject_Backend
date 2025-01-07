<?php

// app/Repositories/Evaluation/DevoirRepository.php
namespace App\Repositories\Evaluation;

use App\Models\Evaluation\Devoir;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IDevoirRepository;

class DevoirRepository extends BaseRepository implements IDevoirRepository
{
    public function __construct(Devoir $model)
    {
        parent::__construct($model);
    }

    public function getDevoirsByModule(int $moduleId)
    {
        return $this->model->where('module_id', $moduleId)
            ->with(['professeur', 'classe'])
            ->orderBy('date_remise', 'desc')
            ->get();
    }

    public function getDevoirsByClass(int $classeId)
    {
        return $this->model->where('classe_id', $classeId)
            ->with(['module', 'professeur'])
            ->orderBy('date_remise', 'desc')
            ->get();
    }

    public function getDevoirsByPeriod(string $startDate, string $endDate)
    {
        return $this->model->whereBetween('date_remise', [$startDate, $endDate])
            ->with(['module', 'classe', 'professeur'])
            ->orderBy('date_remise', 'asc')
            ->get();
    }

    public function assignDevoir(int $classeId, array $devoirData)
    {
        $devoirData['classe_id'] = $classeId;
        return $this->create($devoirData);
    }
}
