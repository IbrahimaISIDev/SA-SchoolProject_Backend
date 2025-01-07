<?php

namespace App\Http\Controllers\Api\Presence;
use App\Services\Presence\ServiceAbsence;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    protected $serviceAbsence;

    public function __construct(ServiceAbsence $serviceAbsence)
    {
        $this->serviceAbsence = $serviceAbsence;
    }

    public function soumettre(Request $request)
    {
        $validated = $request->validate([
            'seance_id' => 'required|exists:seances,id',
            'motif' => 'required|string',
            'justificatif' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $absence = $this->serviceAbsence->soumettreJustification($validated);
        return response()->json($absence, 201);
    }

    public function traiterJustification(Request $request, $absenceId)
    {
        $validated = $request->validate([
            'statut' => 'required|in:ACCEPTE,REFUSE',
            'commentaire' => 'string'
        ]);

        $absence = $this->serviceAbsence->traiterJustification($absenceId, $validated);
        return response()->json($absence);
    }

    public function verifierSeuils()
    {
        $etudiants = $this->serviceAbsence->verifierSeuilsAbsences();
        return response()->json($etudiants);
    }
}
