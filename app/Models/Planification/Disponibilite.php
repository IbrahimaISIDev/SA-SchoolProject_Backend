<?php

namespace App\Models\Planification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'heure_debut',
        'heure_fin',
        'motif',
        'type' // indisponible/disponible
    ];

    // Une disponibilité peut concerner soit une salle soit un professeur
    public function disponible()
    {
        return $this->morphTo();
    }

    // Relations avec la planification
    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
