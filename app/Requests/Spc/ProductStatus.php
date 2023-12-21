<?php

namespace App\Requests\Spc;

use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ProductStatus extends \App\Requests\Spc\Request
{
    public readonly string $brandCode;
    public readonly string $storeCode;
    public readonly string $soldoutType;
    public readonly array $menuCodes;
    public readonly ?string $resetDate;

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
        $this->soldoutType = data_get($valid, 'soldoutType');
        $this->menuCodes = data_get($valid, 'menuCodes');
        $this->resetDate = data_get($valid, 'resetDate');
    }
}