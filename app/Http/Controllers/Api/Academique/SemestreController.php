<?php

namespace App\Http\Controllers\Api\Academique;
use App\Http\Controllers\Api\Academique\Controller;
use App\Services\Academique\ServiceSemestre;
use Illuminate\Http\Request;

class SemestreController extends Controller
{
    protected $semestreService;

    public function __construct(ServiceSemestre $semestreService)
    {
        $this->semestreService = $semestreService;
    }

    public function index()
    {
        try {
            $semestres = $this->semestreService->getAllSemestres();
            return response()->json([
                'status' => 'success',
                'data' => $semestres
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des semestres'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut'
        ]);

        try {
            $semestre = $this->semestreService->createSemestre($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Semestre créé avec succès',
                'data' => $semestre
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la création du semestre'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'libelle' => 'required|string',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut'
        ]);

        try {
            $semestre = $this->semestreService->updateSemestre($id, $validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Semestre mis à jour avec succès',
                'data' => $semestre
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la mise à jour du semestre'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->semestreService->deleteSemestre($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Semestre supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la suppression du semestre'
            ], 500);
        }
    }
}
