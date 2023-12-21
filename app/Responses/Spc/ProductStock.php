<?php

namespace App\Responses\Spc;

use Illuminate\Support\Collection;

class ProductStock
{
    public readonly Collection $stock;

    public function __construct(array $data)
    {
        $this->stock = collect($data)->mapWithKeys(function ($product) {
            return [
                $product['code'] => [
                    'qty' => $product['qty'],
                    'option' => collect(data_get($product, 'options'))->mapWithKeys(function ($option) {
                        return [
                            $option['code'] => [
                                'qty' => $option['qty']
                            ]
                        ];
                    })
                ]
            ];
        });
    }
}