<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Storage;

trait AvecPieceJointe
{
    public function sauvegarderPieceJointe($fichier, $dossier = 'pieces_jointes')
    {
        if ($fichier) {
            $chemin = $fichier->store($dossier, 'public');
            return $chemin;
        }
        return null;
    }

    public function supprimerPieceJointe($chemin)
    {
        if ($chemin && Storage::disk('public')->exists($chemin)) {
            Storage::disk('public')->delete($chemin);
            return true;
        }
        return false;
    }
}