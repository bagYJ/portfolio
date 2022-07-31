<?php

declare(strict_types=1);

namespace App\Response\Retail;

use App\Models\RetailProductOptionGroup;

class ProductOptiongroup
{
    public int $no_group;
    public string $nm_group;
    public string $cd_option_type;
    public int $at_select_min;
    public int $at_select_max;
    public object $product_option_products;

    public function __construct(RetailProductOptionGroup $group, array $stock)
    {
        $this->no_group = $group->no_group;
        $this->nm_group = $group->nm_group;
        $this->cd_option_type = $group->cd_option_type;
        $this->at_select_min = $group->at_select_min;
        $this->at_select_max = $group->at_select_max;
        $this->product_option_products = $group->productOptionProducts->map(function ($option) use ($stock) {
            return (new ProductOptionProduct($option, $stock))->setProductOption();
        });
    }

    public function setProductOptionGroup(): ProductOptiongroup
    {
        return $this;
    }
}
