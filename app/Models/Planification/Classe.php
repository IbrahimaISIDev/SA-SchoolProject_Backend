<?php

namespace App\Models\Planification;
use App\Models\Utilisateurs\Etudiant;
use App\Models\Academique\AnneeAcademique;
use App\Models\Academique\Filiere;
use App\Models\Academique\Niveau;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'filiere_id',
        'niveau_id',
        'annee_academique_id'
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'classe_cours');
    }
}
