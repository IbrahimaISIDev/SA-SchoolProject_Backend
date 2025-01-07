<?php

namespace App\Models\Planification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'numero',
        'capacite',
        'type', // présentiel/en ligne
        'statut' // disponible/en maintenance
    ];

    // Relations
    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }
}