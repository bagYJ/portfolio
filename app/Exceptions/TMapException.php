<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class TMapException extends Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
