<?php

namespace App\Requests\Spc;

use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OrderStatus extends \App\Requests\Spc\Request
{
    public readonly string $brandCode;
    public readonly string $storeCode;
    public readonly string $orderId;
    public readonly string $providerOrderId;
    public readonly string $status;
    public readonly string $statusDateTime;
    public readonly ?string $statusMessage;
    public readonly ?int $deliveryTime;

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
        $this->orderId = data_get($valid, 'orderId');
        $this->providerOrderId = data_get($valid, 'providerOrderId');
        $this->status = data_get($valid, 'status');
        $this->statusDateTime = data_get($valid, 'statusDateTime');
        $this->statusMessage = data_get($valid, 'statusMessage');
        $this->deliveryTime = data_get($valid, 'deliveryTime');
    }
}