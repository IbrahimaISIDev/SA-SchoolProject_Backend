<?php

namespace App\Http\Controllers\Api\Evaluation;
use App\Http\Controllers\Controller;
use App\Services\Evaluation\ServiceNote;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    protected $serviceNote;

    public function __construct(ServiceNote $serviceNote)
    {
        $this->serviceNote = $serviceNote;
    }

    public function saisir(Request $request)
    {
        $validated = $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'notes' => 'required|array',
            'notes.*.etudiant_id' => 'required|exists:utilisateurs,id',
            'notes.*.valeur' => 'required|numeric|min:0|max:20'
        ]);

        $notes = $this->serviceNote->saisirNotes($validated['evaluation_id'], $validated['notes']);
        return response()->json($notes, 201);
    }

    public function genererBulletin(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:utilisateurs,id',
            'semestre_id' => 'required|exists:semestres,id'
        ]);

        $bulletin = $this->serviceNote->genererBulletin($validated);
        return response()->json($bulletin);
    }

    public function exporterBulletin(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:utilisateurs,id',
            'semestre_id' => 'required|exists:semestres,id',
            'format' => 'required|in:pdf,excel'
        ]);

        $fichier = $this->serviceNote->exporterBulletin($validated);
        return response()->download($fichier);
    }
}
