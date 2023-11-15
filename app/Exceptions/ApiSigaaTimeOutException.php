<?php

namespace App\Exceptions;

use Exception;

class ApiSigaaTimeOutException extends Exception
{
    public function __construct($message = "Api do Sigaa está fora do ar.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
