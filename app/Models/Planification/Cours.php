<?php

namespace App\Models\Planification;
use App\Models\Academique\Module;
use App\Models\Utilisateurs\Professeur;
use App\Models\Academique\Semestre;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'professeur_id',
        'semestre_id',
        'quota_horaire',
        'statut'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_cours');
    }

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
