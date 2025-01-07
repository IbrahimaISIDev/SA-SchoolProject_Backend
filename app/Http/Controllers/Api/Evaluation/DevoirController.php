<?php

namespace App\Http\Controllers\Api\Evaluation;

use App\Http\Controllers\Controller;
use App\Services\Evaluation\ServiceDevoir;
use Illuminate\Http\Request;
use App\Models\Evaluation\Devoir;

class DevoirController extends Controller 
{
    protected $serviceDevoir;

    public function __construct(ServiceDevoir $serviceDevoir)
    {
        $this->serviceDevoir = $serviceDevoir;
    }

    public function creer(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'type' => 'required|in:CONTROLE,TD,TP',
            'date' => 'required|date',
            'coefficient' => 'required|numeric',
            'description' => 'required|string'
        ]);

        try {
            $devoir = $this->serviceDevoir->creer($request->all());
            return response()->json([
                'success' => true,
                'devoir' => $devoir
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du devoir'
            ], 500);
        }
    }

    public function modifier(Request $request, $id)
    {
        $request->validate([
            'type' => 'in:CONTROLE,TD,TP',
            'date' => 'date',
            'coefficient' => 'numeric',
            'description' => 'string'
        ]);

        try {
            $devoir = $this->serviceDevoir->modifier($id, $request->all());
            return response()->json([
                'success' => true,
                'devoir' => $devoir
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du devoir'
            ], 500);
        }
    }

    public function supprimer($id)
    {
        try {
            $this->serviceDevoir->supprimer($id);
            return response()->json([
                'success' => true,
                'message' => 'Devoir supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du devoir'
            ], 500);
        }
    }

    public function lister(Request $request)
    {
        $moduleId = $request->query('module_id');
        $type = $request->query('type');

        try {
            $devoirs = $this->serviceDevoir->lister($moduleId, $type);
            return response()->json([
                'success' => true,
                'devoirs' => $devoirs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des devoirs'
            ], 500);
        }
    }
}