<?php

namespace App\Models\Evaluation;
use App\Models\Academique\Module;
use App\Models\Utilisateurs\Professeur;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;
    protected $fillable = [
        'libelle',
        'date',
        'coefficient',
        'type', // Partiel, Final
        'module_id',
        'session' // Normale, Rattrapage
    ];

    // Relations
    public function notes()
    {
        return $this->morphMany(Note::class, 'evaluation');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function surveillants()
    {
        return $this->belongsToMany(Professeur::class, 'surveillance_examen');
    }
}
