<?php

namespace App\Http\Controllers\Api\Evaluation;

use App\Http\Controllers\Controller;
use App\Services\Evaluation\ServiceBulletin;
use Illuminate\Http\Request;
use App\Models\Evaluation\Bulletin;

class BulletinController extends Controller 
{
    protected $serviceBulletin;

    public function __construct(ServiceBulletin $serviceBulletin)
    {
        $this->serviceBulletin = $serviceBulletin;
    }

    public function genererBulletin(Request $request, $etudiantId, $semestreId)
    {
        try {
            $bulletin = $this->serviceBulletin->generer($etudiantId, $semestreId);
            return response()->json([
                'success' => true,
                'bulletin' => $bulletin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du bulletin'
            ], 500);
        }
    }

    public function exporterBulletin($bulletinId)
    {
        try {
            $pdf = $this->serviceBulletin->exporterPDF($bulletinId);
            return $pdf->download('bulletin.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export du bulletin'
            ], 500);
        }
    }

    public function consulterBulletin($etudiantId, $semestreId)
    {
        try {
            $bulletin = $this->serviceBulletin->consulter($etudiantId, $semestreId);
            return response()->json([
                'success' => true,
                'bulletin' => $bulletin
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulletin non trouvé'
            ], 404);
        }
    }
}