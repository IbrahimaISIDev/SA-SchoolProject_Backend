<?php

namespace App\Models\Presence;

use App\Models\Utilisateurs\Etudiant;
use App\Models\Planification\Seance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'seance_id',
        'etudiant_id',
        'justifie'
    ];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function justification()
    {
        return $this->hasOne(Justification::class);
    }
}
