<?php

namespace App\Models\Utilisateurs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'email',
        'telephone',
        'photo',
        'password',
        'type_utilisateur' // ETUDIANT, PROFESSEUR, RESPONSABLE, ATTACHE
    ];

    // Relation polymorphique pour les différents types d'utilisateurs
    public function profilable()
    {
        return $this->morphTo();
    }
}
