<?php

namespace App\Exceptions;

use Exception;

class SigaaLoginTimeOutException extends Exception
{
    public function __construct($message = "Sigaa está fora do ar", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
