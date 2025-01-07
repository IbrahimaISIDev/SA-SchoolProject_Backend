<?php

namespace App\Models\Evaluation;
use App\Models\Academique\Module;
use App\Models\Utilisateurs\Etudiant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    protected $fillable = [
        'valeur',
        'coefficient',
        'observation',
        'date_saisie'
    ];

    // Relations
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function evaluation()
    {
        return $this->morphTo(); // Pour Devoir ou Examen
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
