<?php

namespace App\Support\Enums;

enum StatutCours: string {
    case EN_COURS = 'EN_COURS';
    case TERMINE = 'TERMINE';
    case ANNULE = 'ANNULE';
    case PLANIFIE = 'PLANIFIE';
}