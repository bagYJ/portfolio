<?php

namespace App\Requests\Spc;

use Illuminate\Http\Request;
use Owin\OwinCommonUtil\CodeUtil;

class Uptime extends \App\Requests\Spc\Request
{
    public readonly string $brandCode;
    public readonly string $storeCode;
    public readonly string $orderId;
    public readonly string $arvYn;
    public readonly ?string $arvHm;

    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));
        parent::__construct();

        $this->brandCode = data_get($valid, 'brandCode');
        $this->storeCode = data_get($valid, 'storeCode');
        $this->orderId = CodeUtil::convertOrderCodeToCuSpc(data_get($valid, 'orderId'));

        $this->arvYn = data_get($valid, 'arvYn');
        $this->arvHm = data_get($valid, 'arvHm');
    }
}