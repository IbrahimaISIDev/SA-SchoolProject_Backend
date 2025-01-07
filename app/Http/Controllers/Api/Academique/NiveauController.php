<?php
namespace App\Http\Controllers\Api\Academique;
use App\Http\Controllers\Api\Academique\Controller;
use App\Services\Academique\ServiceNiveau;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    protected $niveauService;

    public function __construct(ServiceNiveau $niveauService)
    {
        $this->niveauService = $niveauService;
    }

    public function index()
    {
        try {
            $niveaux = $this->niveauService->getAllNiveaux();
            return response()->json([
                'status' => 'success',
                'data' => $niveaux
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des niveaux'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:niveaux,libelle',
            'description' => 'nullable|string'
        ]);

        try {
            $niveau = $this->niveauService->createNiveau($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Niveau créé avec succès',
                'data' => $niveau
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création du niveau'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:niveaux,libelle,'.$id,
            'description' => 'nullable|string'
        ]);

        try {
            $niveau = $this->niveauService->updateNiveau($id, $validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Niveau mis à jour avec succès',
                'data' => $niveau
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour du niveau'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->niveauService->deleteNiveau($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Niveau supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression du niveau'
            ], 500);
        }
    }
}
