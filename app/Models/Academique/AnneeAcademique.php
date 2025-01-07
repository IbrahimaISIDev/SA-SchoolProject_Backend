<?php

namespace App\Models\Academique;
use App\Models\Planification\Classe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeAcademique extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin',
        'statut'
    ];

    public function semestres()
    {
        return $this->hasMany(Semestre::class);
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
