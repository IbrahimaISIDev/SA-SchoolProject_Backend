<?php

namespace App\Http\Controllers\Api\Academique;
use App\Http\Controllers\Api\Academique\Controller;
use App\Services\Academique\ServiceModule;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    protected $moduleService;

    public function __construct(ServiceModule $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function index()
    {
        try {
            $modules = $this->moduleService->getAllModules();
            return response()->json([
                'status' => 'success',
                'data' => $modules
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des modules'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:modules,libelle',
            'description' => 'nullable|string',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'coefficient' => 'required|numeric|min:1'
        ]);

        try {
            $module = $this->moduleService->createModule($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Module créé avec succès',
                'data' => $module
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création du module'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:modules,libelle,'.$id,
            'description' => 'nullable|string',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'coefficient' => 'required|numeric|min:1'
        ]);

        try {
            $module = $this->moduleService->updateModule($id, $validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Module mis à jour avec succès',
                'data' => $module
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour du module'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->moduleService->deleteModule($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Module supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression du module'
            ], 500);
        }
    }
}
