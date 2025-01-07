<?php

namespace App\Models\Evaluation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Utilisateurs\Etudiant;
use App\Models\Academique\Module;
use App\Models\Academique\Semestre;
use App\Models\Academique\AnneeAcademique;


class MoyenneModule extends Model
{
    protected $table = 'moyennes_modules';

    protected $fillable = [
        'etudiant_id',
        'module_id',
        'semestre_id',
        'moyenne_devoir',     // Moyenne des devoirs
        'moyenne_examen',     // Note de l'examen
        'moyenne_finale',     // Moyenne finale du module
        'coefficient',        // Coefficient du module
        'credit',            // Crédit du module
        'validation',        // Status de validation du module
        'session',           // Normal ou Rattrapage
        'annee_academique_id'
    ];

    protected $casts = [
        'moyenne_devoir' => 'float',
        'moyenne_examen' => 'float',
        'moyenne_finale' => 'float',
        'coefficient' => 'float',
        'credit' => 'integer',
        'validation' => 'boolean',
    ];

    // Relation avec l'étudiant
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class);
    }

    // Relation avec le module
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // Relation avec le semestre
    public function semestre(): BelongsTo
    {
        return $this->belongsTo(Semestre::class);
    }

    // Relation avec l'année académique
    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class);
    }

    // Méthode pour calculer la moyenne finale
    public function calculerMoyenneFinale(): float
    {
        // La moyenne finale est généralement : (Moyenne devoir * 0.4) + (Note examen * 0.6)
        return ($this->moyenne_devoir * 0.4) + ($this->moyenne_examen * 0.6);
    }

    // Méthode pour vérifier si le module est validé
    public function estValide(): bool
    {
        // Un module est généralement validé si la moyenne finale >= 10
        return $this->moyenne_finale >= 10;
    }

    // Méthode pour calculer les points du module
    public function calculerPoints(): float
    {
        return $this->moyenne_finale * $this->coefficient;
    }

    // Scope pour filtrer par session
    public function scopeSession($query, string $session)
    {
        return $query->where('session', $session);
    }

    // Scope pour filtrer par année académique
    public function scopeAnneeAcademique($query, int $anneeId)
    {
        return $query->where('annee_academique_id', $anneeId);
    }

    // Scope pour obtenir les modules validés
    public function scopeValides($query)
    {
        return $query->where('validation', true);
    }

    // Scope pour obtenir les modules non validés
    public function scopeNonValides($query)
    {
        return $query->where('validation', false);
    }
}