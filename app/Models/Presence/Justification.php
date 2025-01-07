<?php

namespace App\Models\Presence;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'absence_id',
        'motif',
        'piece_jointe',
        'date_soumission',
        'statut', // EN_ATTENTE, ACCEPTEE, REFUSEE
        'date_traitement',
        'commentaire'
    ];

    public function absence()
    {
        return $this->belongsTo(Absence::class);
    }
}
