<?php

declare(strict_types=1);

namespace App\Request\Cu;

use App\Models\RetailOrderProductOption;

class PaymentOption
{
    public ?string $product_code;
    public string $nm_product;
    public int $ct_inven;
    public float $at_price_product;
    public string $yn_event_product;

    public function __construct(RetailOrderProductOption $option)
    {
        $this->product_code = $option->retailProductOption?->no_barcode_opt;
        $this->nm_product = $option->nm_product_opt;
        $this->ct_inven = $option->ct_inven;
        $this->at_price_product = $option->at_price_opt;
        $this->yn_event_product = 'N';
    }

    public function setPaymentOption(): PaymentOption
    {
        return $this;
    }
}
