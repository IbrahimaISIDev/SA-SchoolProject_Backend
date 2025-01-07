<?php

namespace App\Models\Presence;

use App\Models\Utilisateurs\Etudiant;
use App\Models\Planification\Seance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emargement extends Model
{
    use HasFactory;

    protected $fillable = [
        'seance_id',
        'etudiant_id',
        'heure_emargement',
        'statut', // PRESENT, ABSENT, RETARD
        'valide'
    ];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
}
