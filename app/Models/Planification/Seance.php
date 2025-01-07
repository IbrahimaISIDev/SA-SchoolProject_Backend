<?php

namespace App\Models\Planification;

use App\Models\Presence\Emargement;
use App\Models\Presence\Absence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    protected $fillable = [
        'cours_id',
        'salle_id',
        'date',
        'heure_debut',
        'heure_fin',
        'type', // PRESENTIEL, EN_LIGNE
        'statut', // PLANIFIEE, EN_COURS, TERMINEE, ANNULEE
        'nombre_heures'
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function emargements()
    {
        return $this->hasMany(Emargement::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }
}

