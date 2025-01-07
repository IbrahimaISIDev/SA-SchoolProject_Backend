<?php

namespace App\Http\Controllers\Api\Planification;

use App\Http\Controllers\Controller;
use App\Services\Planification\ServiceSeance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SeanceController extends Controller
{
    protected $serviceSeance;

    public function __construct(ServiceSeance $serviceSeance)
    {
        $this->serviceSeance = $serviceSeance;
    }

    public function index(Request $request)
    {
        try {
            $seances = $this->serviceSeance->getSeances(
                $request->query('cours_id'),
                $request->query('date_debut'),
                $request->query('date_fin')
            );
            return response()->json([
                'status' => 'success',
                'data' => $seances
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'salle_id' => 'required|exists:salles,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'type' => 'required|in:PRESENTIEL,EN_LIGNE',
            'nombre_heures' => 'required|numeric|min:1'
        ]);

        try {
            // Vérifier les disponibilités
            $this->serviceSeance->verifierConflits($validated);
            
            // Vérifier le quota horaire restant
            $this->serviceSeance->verifierQuotaHoraire($validated['cours_id'], $validated['nombre_heures']);
            
            $seance = $this->serviceSeance->createSeance($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Séance créée avec succès',
                'data' => $seance
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function annuler(Request $request, $id)
    {
        $validated = $request->validate([
            'motif' => 'required|string'
        ]);

        try {
            $seance = $this->serviceSeance->annulerSeance($id, $validated['motif']);
            return response()->json([
                'status' => 'success',
                'message' => 'Séance annulée avec succès',
                'data' => $seance
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function valider($id)
    {
        try {
            $seance = $this->serviceSeance->validerSeance($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Séance validée avec succès',
                'data' => $seance
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}