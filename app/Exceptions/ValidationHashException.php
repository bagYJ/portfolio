<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

class ValidationHashException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
