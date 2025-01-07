<?php

namespace App\Models\Utilisateurs;
use App\Models\Academique\AnneeAcademique;
use App\Models\Planification\Cours;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Responsable extends Model
{
    use HasFactory;

    protected $fillable = [
        'fonction',
        'date_prise_poste'
    ];

    public function utilisateur()
    {
        return $this->morphOne(Utilisateur::class, 'profilable');
    }

    // Relations avec la planification
    public function coursValides()
    {
        return $this->hasMany(Cours::class, 'validateur_id');
    }

    public function anneesAcademiques()
    {
        return $this->hasMany(AnneeAcademique::class, 'createur_id');
    }
}