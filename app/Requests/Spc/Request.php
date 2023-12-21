<?php

namespace App\Requests\Spc;

class Request
{
    public function __construct()
    {
        $this->orderChannel = getenv('SPC_ORDER_CHANNEL');
    }
}