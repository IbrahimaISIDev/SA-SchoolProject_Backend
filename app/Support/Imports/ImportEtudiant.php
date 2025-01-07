<?php

namespace App\Support\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Utilisateurs\Etudiant;

class ImportEtudiant implements ToModel, WithHeadingRow
{
    private $classe_id;
    
    public function __construct($classe_id)
    {
        $this->classe_id = $classe_id;
    }

    public function model(array $row)
    {
        return new Etudiant([
            'matricule' => $row['matricule'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'dateNaissance' => $row['date_naissance'],
            'lieuNaissance' => $row['lieu_naissance'],
            'classe_id' => $this->classe_id
        ]);
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required|unique:etudiants,matricule',
            'email' => 'required|email|unique:utilisateurs,email',
            'telephone' => 'required|unique:utilisateurs,telephone'
        ];
    }
}