<?php

use App\Models\Presence\Absence;

interface IServiceAbsence {
    public function enregistrer(int $etudiantId, int $seanceId): Absence;
    public function justifier(int $absenceId, array $justification): bool;
    public function validerJustification(int $justificationId, bool $estAcceptee): bool;
    public function calculerHeuresAbsence(int $etudiantId, int $semestreId): int;
    public function verifierSeuils(int $etudiantId): array;
    public function genererAvertissement(int $etudiantId): bool;
    public function genererConvocation(int $etudiantId): bool;
}