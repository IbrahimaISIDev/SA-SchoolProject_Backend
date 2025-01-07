<?php

namespace App\Models\Evaluation;
use App\Models\Academique\Module;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devoir extends Model
{
    use HasFactory;
    protected $fillable = [
        'libelle',
        'date',
        'coefficient',
        'type', // TD, TP, Projet...
        'module_id'
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
}
