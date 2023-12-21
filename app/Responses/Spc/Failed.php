<?php

declare(strict_types=1);

namespace App\Responses\Spc;

use Throwable;

class Failed
{
    public readonly string $resultCode;
    public readonly string $resultMessage;

    public function __construct(Throwable $t)
    {
        $this->resultCode = '9999';
        $this->resultMessage = $t->getMessage();
    }
}
