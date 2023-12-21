<?php

declare(strict_types=1);

namespace App\Responses\Spc;

class Response
{
    public readonly string $resultCode;
    public readonly string $resultMsg;
    public readonly ?array $resultData;

    public function __construct(array $response)
    {
        $this->resultCode = data_get($response, 'resultCode', getenv('SPC_RETURN_TRUE'));
        $this->resultMsg = data_get($response, 'resultMessage');
        $this->resultData = data_get($response, 'resultData');
    }
}
