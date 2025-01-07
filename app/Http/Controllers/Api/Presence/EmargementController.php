<?php

namespace App\Http\Controllers\Api\Presence;
use App\Services\Presence\ServiceEmargement;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmargementController extends Controller
{
    protected $serviceEmargement;

    public function __construct(ServiceEmargement $serviceEmargement)
    {
        $this->serviceEmargement = $serviceEmargement;
    }

    public function marquerPresence(Request $request)
    {
        $validated = $request->validate([
            'seance_id' => 'required|exists:seances,id',
            'etudiant_id' => 'required|exists:utilisateurs,id'
        ]);

        // Vérification du délai de 30 minutes
        if (!$this->serviceEmargement->verifierDelaiEmargement($validated['seance_id'])) {
            return response()->json([
                'message' => 'Délai d\'émargement dépassé'
            ], 422);
        }

        $emargement = $this->serviceEmargement->marquerPresence($validated);
        return response()->json($emargement, 201);
    }

    public function validerEmargements(Request $request)
    {
        $validated = $request->validate([
            'seance_id' => 'required|exists:seances,id'
        ]);

        $this->serviceEmargement->validerEmargements($validated['seance_id']);
        return response()->json(['message' => 'Émargements validés avec succès']);
    }
}
