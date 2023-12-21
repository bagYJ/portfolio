<?php
declare(strict_types=1);

namespace App\Responses\AutoWash;

class Benefit
{
    public readonly string $unit;
    public readonly int $price;

    public function __construct(array $benefit)
    {
        $this->unit = data_get($benefit, 'unit');
        $this->price = data_get($benefit, 'price');
    }
}
