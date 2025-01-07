<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IBaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements IBaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allPagine(int $nombreParPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($nombreParPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->find($id);
        if ($record) {
            $record->update($data);
            return true;
        }
        return false;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function findBy(array $criteria): Collection
    {
        $query = $this->model->query();

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }
}
