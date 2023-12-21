<?php
declare(strict_types=1);

namespace App\Requests\Cu;

use App\Enums\DiscountSale;
use App\Models\RetailOrderProduct;
use App\Models\RetailOrderProductOption;
use Illuminate\Support\Collection;

class OrderProduct
{
    public readonly string $product_code;
    public readonly string $nm_product;
    public readonly float $ct_inven;
    public readonly float $at_price_product;
    public readonly string $yn_event_product;
    public readonly ?string $cd_event_sell;
    public readonly ?Collection $option;

    public function __construct(RetailOrderProduct $product, ?bool $isEventProduct = false)
    {
        $this->product_code = $product->retailProduct->no_barcode;
        $this->nm_product = $product->nm_product;
        $this->ct_inven = $product->ct_inven;
        list($this->at_price_product, $this->yn_event_product) = match ($isEventProduct) {
            true => [0, 'Y'],
            default => [$product->at_price_product, 'N']
        };
        $this->cd_event_sell = $product->cd_discount_sale;
        if ($this->cd_event_sell == DiscountSale::SET->value) {
            $this->option = $product->retailOrderProductOption?->fresh('retailProductOption')->map(function (RetailOrderProductOption $option) {
                return (new OrderProductOption($option));
            });
        }
    }
}
