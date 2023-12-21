<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CustomCuException extends Exception
{
    public function __construct(string $partnerCode, string $shopCode, string $message = "", int $code = 0)
    {
        return base64_encode(json_encode([
            'result_code' => $code,
            'partner_code' => $partnerCode,
            'shop_code' => $shopCode,
            'result_msg' => $message
        ]));
//        parent::__construct($message, $code, $previous);
    }
}
