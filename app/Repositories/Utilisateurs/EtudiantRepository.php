<?php

namespace App\Repositories;

use App\Models\Utilisateurs\Etudiant;
use App\Support\Imports\ImportEtudiant;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\IEtudiantRepository;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Exception;

class EtudiantRepository extends BaseRepository implements IEtudiantRepository
{
    public function __construct(Etudiant $etudiant)
    {
        parent::__construct($etudiant);
    }

    /**
     * Importer des étudiants à partir d'un fichier Excel
     * @param string $fichierExcel Chemin vers le fichier Excel
     * @return array Résultat de l'importation
     * @throws Exception
     */
    public function importerEtudiants(string $fichierExcel)
    {
        try {
            DB::beginTransaction();
            
            $import = new ImportEtudiant();
            Excel::import($import, $fichierExcel);
            
            $resultats = [
                'total' => $import->getRowCount(),
                'succes' => $import->getSuccessCount(),
                'erreurs' => $import->getErrors()
            ];

            DB::commit();
            return $resultats;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erreur lors de l'importation des étudiants: " . $e->getMessage());
        }
    }

    /**
     * Récupérer tous les étudiants d'une classe
     * @param int $classeId Identifiant de la classe
     * @return Collection Collection d'étudiants
     */
    public function getEtudiantsParClasse(int $classeId)
    {
        return $this->model
            ->where('classe_id', $classeId)
            ->with(['absences', 'notes']) // Chargement des relations importantes
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
    }

    /**
     * Changer la classe d'un étudiant
     * @param int $etudiantId Identifiant de l'étudiant
     * @param int $classeId Identifiant de la nouvelle classe
     * @return bool Succès de l'opération
     * @throws Exception
     */
    public function changerClasse(int $etudiantId, int $classeId): bool
    {
        try {
            DB::beginTransaction();

            $etudiant = $this->findById($etudiantId);
            if (!$etudiant) {
                throw new Exception("Étudiant non trouvé");
            }

            // Sauvegarde de l'ancienne classe pour historique si nécessaire
            $ancienneClasseId = $etudiant->classe_id;

            // Mise à jour de la classe
            $etudiant->classe_id = $classeId;
            $etudiant->save();

            // Possibilité d'ajouter ici la création d'un enregistrement dans une table historique
            
            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Erreur lors du changement de classe: " . $e->getMessage());
        }
    }

    /**
     * Extension de la méthode create pour ajouter des validations spécifiques
     * @param array $data Données de l'étudiant
     * @return Etudiant
     */
    public function create(array $data)
    {
        // Validation spécifique si nécessaire
        $this->validateEtudiantData($data);
        
        return parent::create($data);
    }

    /**
     * Extension de la méthode update pour ajouter des validations spécifiques
     * @param int $id Identifiant de l'étudiant
     * @param array $data Nouvelles données
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        // Validation spécifique si nécessaire
        $this->validateEtudiantData($data);
        
        return parent::update($id, $data);
    }

    /**
     * Valider les données d'un étudiant
     * @param array $data Données à valider
     * @throws Exception
     */
    private function validateEtudiantData(array $data)
    {
        // Validation du matricule unique
        if (isset($data['matricule'])) {
            $existant = $this->model
                ->where('matricule', $data['matricule'])
                ->where('id', '!=', $data['id'] ?? 0)
                ->exists();

            if ($existant) {
                throw new Exception("Ce matricule existe déjà");
            }
        }

        // Autres validations spécifiques...
    }
}