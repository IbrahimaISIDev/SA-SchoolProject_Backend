<?php

namespace App\Models\Academique;
use App\Models\Planification\Classe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $fillable = ['libelle'];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class, 'filiere_niveau');
    }
}
