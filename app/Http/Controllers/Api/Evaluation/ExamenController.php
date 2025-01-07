<?php

namespace App\Http\Controllers\Api\Evaluation;

use App\Http\Controllers\Controller;
use App\Services\Evaluation\ServiceExamen;
use Illuminate\Http\Request;
use App\Models\Evaluation\Examen;

class ExamenController extends Controller 
{
    protected $serviceExamen;

    public function __construct(ServiceExamen $serviceExamen)
    {
        $this->serviceExamen = $serviceExamen;
    }

    public function planifier(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'date' => 'required|date',
            'heure_debut' => 'required',
            'duree' => 'required|integer',
            'salle_id' => 'required|exists:salles,id',
            'type' => 'required|in:PARTIEL,FINAL',
            'coefficient' => 'required|numeric'
        ]);

        try {
            $examen = $this->serviceExamen->planifier($request->all());
            return response()->json([
                'success' => true,
                'examen' => $examen
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la planification de l\'examen'
            ], 500);
        }
    }

    public function modifier(Request $request, $id)
    {
        $request->validate([
            'date' => 'date',
            'heure_debut' => 'date_format:H:i',
            'duree' => 'integer',
            'salle_id' => 'exists:salles,id',
            'type' => 'in:PARTIEL,FINAL',
            'coefficient' => 'numeric'
        ]);

        try {
            $examen = $this->serviceExamen->modifier($id, $request->all());
            return response()->json([
                'success' => true,
                'examen' => $examen
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification de l\'examen'
            ], 500);
        }
    }

    public function annuler($id)
    {
        try {
            $this->serviceExamen->annuler($id);
            return response()->json([
                'success' => true,
                'message' => 'Examen annulé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de l\'examen'
            ], 500);
        }
    }

    public function listerParModule($moduleId)
    {
        try {
            $examens = $this->serviceExamen->listerParModule($moduleId);
            return response()->json([
                'success' => true,
                'examens' => $examens
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des examens'
            ], 500);
        }
    }
}