<?php
namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IBaseRepository
{
    public function all();
    public function allPagine(int $nombreParPage = 10): LengthAwarePaginator;
    public function create(array $data);
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function find(int $id);
    public function findById(int $id);
}
//public function tout(array $colonnes = ['*'], array $relations = []): Collection;
// public function findBy(array $criteria); 