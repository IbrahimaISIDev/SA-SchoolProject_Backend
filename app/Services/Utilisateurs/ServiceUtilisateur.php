<?php

namespace App\Services;

use App\Repositories\Interfaces\IUtilisateurRepository;
use App\Services\Interfaces\IServiceUtilisateur;
use App\Models\Utilisateurs\Utilisateur;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceUtilisateur implements IServiceUtilisateur
{
    protected IUtilisateurRepository $utilisateurRepository;

    public function __construct(IUtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    /**
     * Créer un utilisateur
     *
     * @param array $data
     * @return Utilisateur
     */
    public function creer(array $data): Utilisateur
    {
        // Validation des données
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Création de l'utilisateur
        return $this->utilisateurRepository->create($data);
    }

    /**
     * Modifier les informations d'un utilisateur
     *
     * @param int $id
     * @param array $data
     * @return Utilisateur
     */
    public function modifier(int $id, array $data): Utilisateur
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Appel à la méthode update de votre repository
        $utilisateur = $this->utilisateurRepository->update($id, $data);

        if ($utilisateur instanceof Utilisateur) {
            return $utilisateur;
        } else {
            throw new ModelNotFoundException("Utilisateur avec ID $id non trouvé.");
        }
    }


    /**
     * Supprimer un utilisateur
     *
     * @param int $id
     * @return bool
     */

    public function supprimer(int $id): bool
    {
        $utilisateur = $this->utilisateurRepository->find($id);

        if (!$utilisateur) {
            throw new ModelNotFoundException("Utilisateur avec ID $id non trouvé.");
        }

        return $this->utilisateurRepository->delete($id);
    }


    /**
     * Récupérer un utilisateur par ID
     *
     * @param int $id
     * @return Utilisateur|null
     */
    public function recuperer(int $id): ?Utilisateur
    {
        return $this->utilisateurRepository->find($id);
    }

    /**
     * Récupérer un utilisateur par ID (alias)
     *
     * @param int $id
     * @return Utilisateur|null
     */
    public function recupererParId(int $id): ?Utilisateur
    {
        return $this->recuperer($id);
    }

    /**
     * Rechercher des utilisateurs en fonction de critères
     *
     * @param array $criteres
     * @return Collection
     */
    public function rechercher(array $criteres): Collection
    {
        return $this->utilisateurRepository->findByCriteria($criteres);
    }

    /**
     * Lister tous les utilisateurs
     *
     * @return Collection
     */
    public function lister(): Collection
    {
        return $this->utilisateurRepository->all();
    }

    /**
     * Récupérer tous les utilisateurs (alias)
     *
     * @return Collection
     */
    public function recupererTout(): Collection
    {
        return $this->lister();
    }

    /**
     * Récupérer les utilisateurs par type
     *
     * @param string $type
     * @return Collection
     */
    public function recupererParType(string $type): Collection
    {
        return $this->utilisateurRepository->findByType($type);
    }

    /**
     * Modifier le mot de passe d'un utilisateur
     *
     * @param int $id
     * @param string $nouveauMotDePasse
     * @return bool
     */
    public function modifierMotDePasse(int $id, string $nouveauMotDePasse): bool
    {
        $hashedPassword = Hash::make($nouveauMotDePasse);
        $utilisateur = $this->utilisateurRepository->updatePassword($id, $hashedPassword);

        if (!$utilisateur) {
            throw new ModelNotFoundException("Utilisateur avec ID $id non trouvé.");
        }

        return true;
    }

    /**
     * Vérifier l'authentification de l'utilisateur avec email et mot de passe
     *
     * @param string $email
     * @param string $motDePasse
     * @return Utilisateur|null
     */
    public function verifierAuthentification(string $email, string $motDePasse): ?Utilisateur
    {
        $utilisateur = $this->utilisateurRepository->findByEmail($email);

        if ($utilisateur && Hash::check($motDePasse, $utilisateur->password)) {
            return $utilisateur;
        }

        return null;
    }
}
