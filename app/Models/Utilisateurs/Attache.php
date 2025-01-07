<?php

namespace App\Models\Utilisateurs;
use App\Models\Presence\Emargement;
use App\Models\Presence\Justification;
use App\Models\Planification\Seance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attache extends Model
{
    use HasFactory;

    protected $fillable = [
        'fonction',
        'date_embauche'
    ];

    // Relation avec le modèle Utilisateur
    public function utilisateur()
    {
        return $this->morphOne(Utilisateur::class, 'profilable');
    }

    // Relations avec les validations
    public function validationsEmargements()
    {
        return $this->hasMany(Emargement::class, 'validateur_id');
    }

    public function validationsJustifications()
    {
        return $this->hasMany(Justification::class, 'validateur_id');
    }

    public function validationsSeances()
    {
        return $this->hasMany(Seance::class, 'validateur_id');
    }
}