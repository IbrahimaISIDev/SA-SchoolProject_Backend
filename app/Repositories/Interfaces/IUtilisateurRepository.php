<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


interface IUtilisateurRepository extends IBaseRepository
{
    /**
     * Rechercher un utilisateur par email
     * @param string $email
     * @return Model|null
     */
    public function trouverParEmail(string $email);

    /**
     * Rechercher un utilisateur par matricule
     * @param string $matricule
     * @return Model|null
     */
    public function trouverParMatricule(string $matricule);

    /**
     * Rechercher un utilisateur par email
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email);

    /**
     * Rechercher des utilisateurs par des critères
     * @param array $criteria
     * @return Collection
     */
    public function findByCriteria(array $criteria): Collection;

    /**
     * Rechercher des utilisateurs par type
     * @param string $type
     * @return Collection
     */
    public function findByType(string $type): Collection;

    /**
     * Mettre à jour le mot de passe d'un utilisateur
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function updatePassword(int $id, string $password): bool;
}
