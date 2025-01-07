<?php

namespace App\Support\Enums;

enum TypeUtilisateur: string {
    case ETUDIANT = 'ETUDIANT';
    case PROFESSEUR = 'PROFESSEUR';
    case RESPONSABLE = 'RESPONSABLE';
    case ATTACHE = 'ATTACHE';
}