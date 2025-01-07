<?php
// app/Services/Exceptions/ConflitPlanificationException.php
namespace App\Services\Exceptions;

use Exception;

class ConflitPlanificationException extends Exception
{
    protected $message = 'Conflit détecté dans la planification des cours.';
}
