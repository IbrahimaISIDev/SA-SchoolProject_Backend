<?php

namespace App\Http\Controllers\Api\Academique;
use App\Http\Controllers\Api\Academique\Controller;
use App\Services\Academique\ServiceFiliere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    protected $filiereService;

    public function __construct(ServiceFiliere $filiereService)
    {
        $this->filiereService = $filiereService;
    }

    public function index()
    {
        try {
            $filieres = $this->filiereService->getAllFilieres();
            return response()->json([
                'status' => 'success',
                'data' => $filieres
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des filières'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:filieres,libelle',
            'description' => 'nullable|string'
        ]);

        try {
            $filiere = $this->filiereService->createFiliere($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Filière créée avec succès',
                'data' => $filiere
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création de la filière'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:filieres,libelle,'.$id,
            'description' => 'nullable|string'
        ]);

        try {
            $filiere = $this->filiereService->updateFiliere($id, $validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Filière mise à jour avec succès',
                'data' => $filiere
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour de la filière'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->filiereService->deleteFiliere($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Filière supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression de la filière'
            ], 500);
        }
    }
}
