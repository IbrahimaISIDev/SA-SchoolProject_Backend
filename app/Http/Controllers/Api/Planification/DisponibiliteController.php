<?php

namespace App\Http\Controllers\Api\Planification;

use App\Http\Controllers\Controller;
use App\Services\Planification\ServiceDisponibilite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DisponibiliteController extends Controller
{
    protected $serviceDisponibilite;

    public function __construct(ServiceDisponibilite $serviceDisponibilite)
    {
        $this->serviceDisponibilite = $serviceDisponibilite;
    }

    public function index(Request $request)
    {
        try {
            $disponibilites = $this->serviceDisponibilite->getDisponibilites(
                $request->query('professeur_id'),
                $request->query('date_debut'),
                $request->query('date_fin')
            );
            return response()->json([
                'status' => 'success',
                'data' => $disponibilites
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
            'professeur_id' => 'required|exists:professeurs,id',
            'jour' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut'
        ]);

        try {
            $disponibilite = $this->serviceDisponibilite->createDisponibilite($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Disponibilité enregistrée avec succès',
                'data' => $disponibilite
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function verifierDisponibilite(Request $request)
    {
        $validated = $request->validate([
            'professeur_id' => 'required|exists:professeurs,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut'
        ]);

        try {
            $disponible = $this->serviceDisponibilite->verifierDisponibilite($validated);
            return response()->json([
                'status' => 'success',
                'disponible' => $disponible
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
