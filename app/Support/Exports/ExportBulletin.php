<?php

namespace App\Support\Exports;
use App\Models\Evaluation\Note;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBulletin implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $etudiant_id;
    private $semestre_id;

    public function __construct($etudiant_id, $semestre_id)
    {
        $this->etudiant_id = $etudiant_id;
        $this->semestre_id = $semestre_id;
    }

    public function collection()
    {
        // Récupération des notes de l'étudiant
        return Note::with(['module', 'typeEvaluation'])
            ->where('etudiant_id', $this->etudiant_id)
            ->where('semestre_id', $this->semestre_id)
            ->get()
            ->map(function ($note) {
                return [
                    'Module' => $note->module->libelle,
                    'Type' => $note->typeEvaluation->libelle,
                    'Note' => $note->valeur,
                    'Coefficient' => $note->coefficient,
                    'Moyenne' => $note->valeur * $note->coefficient
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Module',
            'Type Évaluation',
            'Note',
            'Coefficient',
            'Moyenne'
        ];
    }
}