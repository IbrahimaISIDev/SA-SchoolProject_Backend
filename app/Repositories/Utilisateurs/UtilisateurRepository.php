<?php

namespace App\Repositories\Utilisateurs;

use App\Models\Utilisateurs\Utilisateur;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IUtilisateurRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UtilisateurRepository extends BaseRepository implements IUtilisateurRepository
{
    public function __construct(Utilisateur $model)
    {
        parent::__construct($model);
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
        $model = $this->model->find($id);
        if ($model) {
            return $model->update($data);
        }
        return false;
    }
    // public function update(int $id, array $data): ?Utilisateur {
    //     $utilisateur = Utilisateur::find($id);
    
    //     if ($utilisateur) {
    //         $utilisateur->update($data);
    //         return $utilisateur;  // Retourne l'objet Utilisateur mis à jour
    //     }
    
    //     return null;  // Retourne null si l'utilisateur n'est pas trouvé
    // }
    

    public function delete(int $id): bool
    {
        $model = $this->model->find($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    // public function findBy(array $criteria)
    // {
    //     $query = $this->model->query();
    //     foreach ($criteria as $key => $value) {
    //         $query->where($key, $value);
    //     }
    //     return $query->get();
    // }

    public function trouverParEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function trouverParMatricule(string $matricule)
    {
        return $this->model->where('matricule', $matricule)->first();
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByCriteria(array $criteria): Collection
    {
        $query = $this->model->query();

        foreach ($criteria as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get();
    }

    public function findByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }

    public function updatePassword(int $id, string $password): bool
    {
        $utilisateur = $this->model->find($id);
        if ($utilisateur) {
            $utilisateur->password = $password;
            return $utilisateur->save();
        }
        return false;
    }
}
