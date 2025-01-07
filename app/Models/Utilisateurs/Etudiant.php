<?php

namespace App\Models\Utilisateurs;
use App\Models\Presence\Emargement;
use App\Models\Presence\Justification;
use App\Models\Presence\Absence;
use App\Models\Planification\Classe;
use App\Models\Evaluation\Note;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'classe_id',
        'date_naissance',
        'lieu_naissance',
        'adresse'
    ];

    public function utilisateur()
    {
        return $this->morphOne(Utilisateur::class, 'profilable');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function emargements()
    {
        return $this->hasMany(Emargement::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function justifications()
    {
        return $this->hasMany(Justification::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
