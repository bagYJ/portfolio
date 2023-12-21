<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CustomSpcException extends Exception
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        return response()->json([
            'resultCode' => '9999',
            'resultMessage' => $message,
        ]);
    }
}
