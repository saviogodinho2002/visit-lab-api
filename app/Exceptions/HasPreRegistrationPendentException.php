<?php

namespace App\Exceptions;

use Exception;

class HasPreRegistrationPendentException extends Exception
{
    public function __construct($message = "Ja existe um pré registro pendente para este usuário", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
