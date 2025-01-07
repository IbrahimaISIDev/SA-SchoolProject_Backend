<?php

namespace App\Models\Evaluation;
use App\Models\Evaluation\MoyenneModule;
use App\Models\Utilisateurs\Etudiant;
use App\Models\Academique\Semestre;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulletin extends Model
{
    use HasFactory;
    protected $fillable = [
        'semestre_id',
        'etudiant_id',
        'moyenne_generale',
        'rang',
        'observation',
        'date_generation'
    ];

    // Relations
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    public function moyennesModules()
    {
        return $this->hasMany(MoyenneModule::class);
    }
}
