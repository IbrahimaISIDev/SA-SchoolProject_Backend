<?php
use Illuminate\Database\Eloquent\Collection;

interface IServiceEmargement {
    public function creerListeEmargement(int $seanceId): Collection;
    public function marquerPresence(int $etudiantId, int $seanceId): bool;
    public function validerEmargement(int $seanceId): bool;
    public function verifierDelai(int $seanceId): bool;
    public function recupererPresencesSeance(int $seanceId): Collection;
    public function genererRapportPresence(int $classeId, int $semestreId): array;
}