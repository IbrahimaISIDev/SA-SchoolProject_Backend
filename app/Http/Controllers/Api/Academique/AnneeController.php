<?php

namespace App\Http\Controllers\Api\Academique;
use App\Http\Controllers\Api\Academique\Controller;
use App\Services\Academique\ServiceAnnee;
use Illuminate\Http\Request;

class AnneeController extends Controller
{
    protected $serviceAnnee;

    public function __construct(ServiceAnnee $serviceAnnee)
    {
        $this->serviceAnnee = $serviceAnnee;
    }

    public function creer(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:annees_academiques',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut'
        ]);

        $annee = $this->serviceAnnee->creer($validated);

        return response()->json($annee, 201);
    }

    public function lister()
    {
        $annees = $this->serviceAnnee->lister();
        return response()->json($annees);
    }

    public function afficher($id)
    {
        $annee = $this->serviceAnnee->trouver($id);
        return response()->json($annee);
    }

    public function modifier(Request $request, $id)
    {
        $validated = $request->validate([
            'libelle' => 'string|unique:annees_academiques',
            'date_debut' => 'date',
            'date_fin' => 'date|after:date_debut'
        ]);

        $annee = $this->serviceAnnee->modifier($id, $validated);
        return response()->json($annee);
    }
}
