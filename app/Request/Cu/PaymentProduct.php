<?php

declare(strict_types=1);

namespace App\Request\Cu;

use App\Enums\DiscountSale;
use App\Models\RetailOrderProduct;

class PaymentProduct
{
    public string $product_code;
    public string $nm_product;
    public float $ct_inven;
    public float $at_price_product;
    public string $yn_event_product;
    public ?string $cd_event_sell;
    public ?array $option;

    public function __construct(RetailOrderProduct $product, string $ynEventProduct = 'N')
    {
        $this->product_code = $product->retailProduct->no_barcode;
        $this->nm_product = $product->nm_product;
        $this->ct_inven = $product->ct_inven;
        $this->at_price_product = $ynEventProduct == 'N' ? $product->at_price_product : 0;
        $this->yn_event_product = $ynEventProduct;
        $this->cd_event_sell = $product->cd_discount_sale;
        if ($product->cd_discount_sale == DiscountSale::SET->value) {
            $product->retailOrderProductOption->fresh('retailProductOption')->whenNotEmpty(function ($options) {
                $this->option = $options->map(function ($option) {
                    return (new PaymentOption($option));
                })->all();
            });
        }
    }

    public function setPaymentProduct(): PaymentProduct
    {
        return $this;
    }
}
