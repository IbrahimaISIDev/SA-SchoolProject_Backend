<?php

namespace App\Support\Enums;

enum StatutUtilisateur: string {
    case ACTIF = 'ACTIF';
    case INACTIF = 'INACTIF';
    case SUSPENDU = 'SUSPENDU';
}