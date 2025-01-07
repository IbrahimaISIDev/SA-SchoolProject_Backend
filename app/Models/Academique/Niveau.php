<?php

namespace App\Models\Academique;
use App\Models\Planification\Classe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;

    protected $fillable = ['libelle'];

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'filiere_niveau');
    }

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }
}
