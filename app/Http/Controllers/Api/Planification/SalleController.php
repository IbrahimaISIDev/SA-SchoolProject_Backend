<?php

namespace App\Http\Controllers\Api\Planification;

use App\Http\Controllers\Controller;
use App\Services\Planification\ServiceSalle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SalleController extends Controller
{
    protected $serviceSalle;

    public function __construct(ServiceSalle $serviceSalle)
    {
        $this->serviceSalle = $serviceSalle;
    }

    public function index()
    {
        try {
            $salles = $this->serviceSalle->getAllSalles();
            return response()->json([
                'status' => 'success',
                'data' => $salles
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
            'nom' => 'required|string|max:255',
            'numero' => 'required|string|unique:salles',
            'nombre_places' => 'required|integer|min:1'
        ]);

        try {
            $salle = $this->serviceSalle->createSalle($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Salle créée avec succès',
                'data' => $salle
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
            'salle_id' => 'required|exists:salles,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut'
        ]);

        try {
            $disponible = $this->serviceSalle->verifierDisponibilite($validated);
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