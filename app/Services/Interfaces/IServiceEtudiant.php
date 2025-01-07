<?php
use Illuminate\Database\Eloquent\Collection;
use App\Models\Utilisateurs\Etudiant;

interface IServiceEtudiant extends IServiceUtilisateur {
    public function inscrireEtudiant(array $data): Etudiant;
    public function importerEtudiants(UploadedFile $fichier): Collection;
    public function changerClasse(int $etudiantId, int $classeId): bool;
    public function recupererParClasse(int $classeId): Collection;
    public function recupererBulletins(int $etudiantId): Collection;
    public function calculerMoyenne(int $etudiantId, int $semestreId): float;
    public function recupererAbsences(int $etudiantId): Collection;
    public function recupererEmploiDuTemps(int $etudiantId): Collection;
}