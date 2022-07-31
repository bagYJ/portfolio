<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class MobilXException extends Exception
{
    public $context;

    public function __construct($message = null, $code = 0, Exception $previous = null, $context=null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }
}
