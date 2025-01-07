<?php
namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Academique\Controller;
use App\Services\Academique\ServiceInscription;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    protected $inscriptionService;

    public function __construct(ServiceInscription $inscriptionService)
    {
        $this->inscriptionService = $inscriptionService;
    }

    public function importerEtudiants(Request $request)
    {
        $request->validate([
            'fichier' => 'required|file|mimes:xlsx,xls',
            'classe_id' => 'required|exists:classes,id'
        ]);

        try {
            $resultat = $this->inscriptionService->importerEtudiants($request->file('fichier'), $request->classe_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Importation réussie',
                'data' => $resultat
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'importation des étudiants'
            ], 500);
        }
    }

    public function inscrireEtudiant(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|unique:etudiants,matricule',
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:utilisateurs,email',
            'telephone' => 'required|string',
            'classe_id' => 'required|exists:classes,id',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        try {
            $etudiant = $this->inscriptionService->inscrireEtudiant($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Étudiant inscrit avec succès',
                'data' => $etudiant
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de l\'inscription de l\'étudiant'
            ], 500);
        }
    }
}
