<?php

namespace App\Requests\Spc;

use App\Exceptions\CustomSpcException;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ShopStatus extends \App\Requests\Spc\Request
{
    public readonly string $brandCode;
    public readonly string $orderType;
    public readonly string $storeStatus;
    public readonly array $storeCodes;

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
        $this->orderType = data_get($valid, 'orderType');
        $this->storeStatus = data_get($valid, 'storeStatus');
        $this->storeCodes = data_get($valid, 'storeCodes');
    }
}