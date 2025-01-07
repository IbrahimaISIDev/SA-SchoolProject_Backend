<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Utilisateurs\Utilisateur;

interface IServiceUtilisateur {
    public function creer(array $data): Utilisateur;
    public function modifier(int $id, array $data): Utilisateur;
    public function supprimer(int $id): bool;
    public function recupererParId(int $id): ?Utilisateur;
    public function rechercher(array $criteres);
    public function recuperer(int $id): ?Utilisateur;
    public function lister(): Collection;
    public function recupererTout(): Collection;
    public function recupererParType(string $type): Collection;
    public function modifierMotDePasse(int $id, string $nouveauMotDePasse): bool;
    public function verifierAuthentification(string $email, string $motDePasse): ?Utilisateur;
}
