<?php

namespace App\Http\Controllers\Api\Planification;

use App\Http\Controllers\Controller;
use App\Models\Planification\Classe;
use App\Services\Planification\ServiceClasse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClasseController extends Controller
{
    protected $serviceClasse;

    public function __construct(ServiceClasse $serviceClasse)
    {
        $this->serviceClasse = $serviceClasse;
    }

    public function index()
    {
        try {
            $classes = $this->serviceClasse->getAllClasses();
            return response()->json([
                'status' => 'success',
                'data' => $classes
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
            'libelle' => 'required|string|max:255',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id'
        ]);

        try {
            $classe = $this->serviceClasse->createClasse($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Classe créée avec succès',
                'data' => $classe
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $classe = $this->serviceClasse->getClasseById($id);
            return response()->json([
                'status' => 'success',
                'data' => $classe
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'libelle' => 'sometimes|required|string|max:255',
            'filiere_id' => 'sometimes|required|exists:filieres,id',
            'niveau_id' => 'sometimes|required|exists:niveaux,id'
        ]);

        try {
            $classe = $this->serviceClasse->updateClasse($id, $validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Classe mise à jour avec succès',
                'data' => $classe
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $this->serviceClasse->deleteClasse($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Classe supprimée avec succès'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
