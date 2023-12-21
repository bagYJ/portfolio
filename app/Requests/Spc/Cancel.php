<?php

namespace App\Requests\Spc;

use App\Enums\SpcCancelType;
use App\Utils\Code;
use Illuminate\Http\Request;
use Owin\OwinCommonUtil\CodeUtil;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Cancel extends \App\Requests\Spc\Request
{
    public readonly string $brandCode;
    public readonly string $storeCode;
    public readonly string $orderId;
    public readonly string $cancelType;
    public readonly ?string $cancelMessage;

    /**
     * @param Request $request
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Request $request)
    {
        $valid = $request->validate(config('rules')->get(__CLASS__));
        parent::__construct();

        $this->brandCode = data_get($valid, 'brandCode');
        $this->storeCode = data_get($valid, 'storeCode');
        $this->orderId = CodeUtil::convertOrderCodeToCuSpc(data_get($valid, 'orderId'));
        $this->cancelType = data_get($valid, 'cancelType') ?? SpcCancelType::cancel_order->name;
        $this->cancelMessage = match (!empty(data_get($valid, 'cancelType'))) {
            true => Code::code(SpcCancelType::case(data_get($valid, 'cancelType'))->value),
            default => ""
        };
    }
}