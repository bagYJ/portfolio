<?php

namespace App\Responses\Spc;

class Stock extends Response
{
    public readonly ProductStock $stock;

    public function __construct(array $response)
    {
        parent::__construct($response);
        $data = data_get($response, 'resultData');
        $this->stock = new ProductStock(data_get($data, 'menus'));
    }
}