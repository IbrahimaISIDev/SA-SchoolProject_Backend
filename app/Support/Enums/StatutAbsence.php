<?php

namespace App\Support\Enums;

enum StatutAbsence: string {
    case NON_JUSTIFIEE = 'NON_JUSTIFIEE';
    case EN_ATTENTE = 'EN_ATTENTE';
    case JUSTIFIEE = 'JUSTIFIEE';
    case REJETEE = 'REJETEE';
}