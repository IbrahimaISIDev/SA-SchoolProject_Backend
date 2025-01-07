<?php

namespace App\Support\Exports;
use App\Models\Presence\Emargement;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPresence implements FromCollection, WithHeadings
{
    private $seance_id;

    public function __construct($seance_id)
    {
        $this->seance_id = $seance_id;
    }

    public function collection()
    {
        // Récupération des présences pour la séance
        return Emargement::with(['etudiant', 'seance'])
            ->where('seance_id', $this->seance_id)
            ->get()
            ->map(function ($emargement) {
                return [
                    'Matricule' => $emargement->etudiant->matricule,
                    'Nom' => $emargement->etudiant->nom,
                    'Prénom' => $emargement->etudiant->prenom,
                    'Heure Pointage' => $emargement->heure_pointage,
                    'Statut' => $emargement->present ? 'Présent' : 'Absent'
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Heure Pointage',
            'Statut'
        ];
    }
}