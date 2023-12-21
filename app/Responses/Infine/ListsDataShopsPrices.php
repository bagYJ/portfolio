<?php
declare(strict_types=1);

namespace App\Responses\Infine;

class ListsDataShopsPrices
{
    public readonly string $cd_gas_kind;
    public readonly float $at_price;
    public readonly string $dt_trade;
    public readonly string $tm_trade;

    public function __construct(array $price)
    {
        $this->cd_gas_kind = data_get($price, 'cd_gas_kind');
        $this->at_price = (float)data_get($price, 'at_price');
        $this->dt_trade = data_get($price, 'dt_trade');
        $this->tm_trade = data_get($price, 'tm_trade');
    }
}
