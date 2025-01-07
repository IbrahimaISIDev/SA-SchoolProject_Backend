<?php

namespace App\Models\Academique;
use App\Models\Planification\Cours;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'annee_academique_id',
        'date_debut',
        'date_fin'
    ];

    public function anneeAcademique()
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
}
