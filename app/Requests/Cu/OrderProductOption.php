<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use App\Models\RetailOrderProductOption;

class OrderProductOption
{
    public readonly ?string $product_code;
    public readonly string $nm_product;
    public readonly int $ct_inven;
    public readonly float $at_price_product;
    public readonly string $yn_event_product;

    public function __construct(RetailOrderProductOption $option)
    {
        $this->product_code = $option->retailProductOption?->no_barcode_opt;
        $this->nm_product = $option->nm_product_opt;
        $this->ct_inven = $option->ct_inven;
        $this->at_price_product = $option->at_price_opt;
        $this->yn_event_product = 'N';
    }
}
