<?php

namespace App\Http\Controllers\Api\Planification;
use App\Services\Planification\ServiceCours;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Planification\Cours;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    protected $serviceCours;

    public function __construct(ServiceCours $serviceCours)
    {
        $this->serviceCours = $serviceCours;
    }

    public function planifier(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'professeur_id' => 'required|exists:utilisateurs,id',
            'quota_horaire' => 'required|integer|min:1',
            'semestre_id' => 'required|exists:semestres,id',
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id'
        ]);

        // Vérification des conflits de ressources
        if ($this->serviceCours->verifierConflits($validated)) {
            return response()->json([
                'message' => 'Conflit détecté dans la planification'
            ], 422);
        }

        $cours = $this->serviceCours->planifier($validated);
        return response()->json($cours, 201);
    }

    public function planifierSeance(Request $request, $coursId)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'salle_id' => 'required|exists:salles,id',
            'type' => 'required|in:PRESENTIEL,EN_LIGNE'
        ]);

        // Vérification du quota horaire restant
        if (!$this->serviceCours->verifierQuotaHoraire($coursId, $validated)) {
            return response()->json([
                'message' => 'Quota horaire dépassé'
            ], 422);
        }

        $seance = $this->serviceCours->planifierSeance($coursId, $validated);
        return response()->json($seance, 201);
    }

    public function filtrerParEtat(Request $request)
    {
        $validated = $request->validate([
            'statut' => 'required|in:EN_COURS,TERMINE'
        ]);

        $cours = $this->serviceCours->filtrerParEtat($validated['statut']);
        return response()->json($cours);
    }

    public function heuresEffectuees(Request $request, $professeurId)
    {
        $validated = $request->validate([
            'module_id' => 'exists:modules,id',
            'mois' => 'required|date_format:Y-m'
        ]);

        $heures = $this->serviceCours->calculerHeuresEffectuees(
            $professeurId,
            $validated['mois'],
            $validated['module_id'] ?? null
        );

        return response()->json($heures);
    }

    public function annulerSeance($seanceId)
    {
        $this->serviceCours->annulerSeance($seanceId);
        return response()->json(['message' => 'Séance annulée avec succès']);
    }
}
