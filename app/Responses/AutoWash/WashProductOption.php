<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class WashProductOption
{
    public readonly int $no;
    public readonly int $no_option;
    public readonly int $no_shop;
    public readonly int $no_product;
    public readonly string $nm_option;
    public readonly int $at_price;
    public readonly int $yn_status;

    public function __construct(array $option)
    {
        $this->no = data_get($option, 'no');
        $this->no_option = data_get($option, 'no_option');
        $this->no_shop = data_get($option, 'no_shop');
        $this->no_product = data_get($option, 'no_product');
        $this->nm_option = data_get($option, 'nm_option');
        $this->at_price = data_get($option, 'at_price');
        $this->yn_status = data_get($option, 'yn_status');
    }
}
