<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class WashProduct
{
    public readonly int $no;
    public readonly int $no_product;
    public readonly int $no_shop;
    public readonly string $nm_product;
    public readonly int $at_price;
    public readonly int $cd_car_kind;
    public readonly int $yn_status;
    public readonly WashProductOption $wash_product_option;

    public function __construct(array $product)
    {
        $this->no = data_get($product, 'no');
        $this->no_product = data_get($product, 'no_product');
        $this->no_shop = data_get($product, 'no_shop');
        $this->nm_product = data_get($product, 'nm_product');
        $this->at_price = data_get($product, 'at_price');
        $this->cd_car_kind = data_get($product, 'cd_car_kind');
        $this->yn_status = data_get($product, 'yn_status');
        $this->wash_product_option = new WashProductOption(data_get($product, 'wash_product_option'));
    }
}
