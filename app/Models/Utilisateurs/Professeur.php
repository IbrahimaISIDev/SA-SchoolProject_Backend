<?php

namespace App\Models\Utilisateurs;
use App\Models\Planification\Cours;
use App\Models\Planification\Disponibilite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'specialite',
        'grade',
        'date_embauche',
        'cv'
    ];

    public function utilisateur()
    {
        return $this->morphOne(Utilisateur::class, 'profilable');
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }

    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }
}
