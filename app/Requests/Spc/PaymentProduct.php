<?php

declare(strict_types=1);

namespace App\Requests\Spc;

use App\Models\OrderProduct;
use Illuminate\Support\Collection;

class PaymentProduct
{
    public string $code;
    public string $name;
    public int $ea;
    public int $price;
    public ?array $options;

    public function __construct(OrderProduct $product)
    {
        $this->code = $product->product->cd_spc;
        $this->name = $product->product->nm_product;
        $this->ea = $product->ct_inven;
        $this->price = intval($product->at_price_product);
        $this->options = array();

        if (!empty($product->options)) {
            $this->options = collect(json_decode($product->options, true))->map(function ($option) {
                return [
                    'code' => $option['cd_spc'],
                    'name' => $option['nm_option'],
                    'ea' => $option['ea'] ?? 1,
                    'price' => intval($option['add_price']),
                    'group' => $option['nm_option_group'],
                ];
            })->toArray();
        }


    }

    public function setPaymentProduct(): PaymentProduct
    {
        return $this;
    }
}
