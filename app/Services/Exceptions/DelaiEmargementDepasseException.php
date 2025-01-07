<?php
// app/Services/Exceptions/DelaiEmargementDepasseException.php
namespace App\Services\Exceptions;

use Exception;

class DelaiEmargementDepasseException extends Exception {
    protected $message = 'Le délai d\'émargement est dépassé.';
}
