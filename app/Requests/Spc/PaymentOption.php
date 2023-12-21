<?php

declare(strict_types=1);

namespace App\Requests\Spc;

class PaymentOption
{
    public readonly string $code;
    public readonly string $name;
    public readonly int $ea;
    public readonly int $price;
    public readonly ?string $group;

    public function __construct(array $option)
    {
        $this->code = data_get($option, 'cd_spc');
        $this->name = data_get($option, 'nm_option');
        $this->ea = 1;
        $this->price = intval(data_get($option, 'add_price'));
        $this->group = data_get($option, 'nm_option_group');
    }
}
