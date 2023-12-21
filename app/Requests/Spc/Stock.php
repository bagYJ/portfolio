<?php

namespace App\Requests\Spc;

use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Stock extends \App\Requests\Spc\Request
{
    public readonly string $brandCode;
    public readonly string $storeCode;
    public readonly array $menus;

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
        $this->menus = data_get($valid, 'menus');
    }
}