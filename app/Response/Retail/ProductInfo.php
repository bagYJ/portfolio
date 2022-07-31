<?php

declare(strict_types=1);

namespace App\Response\Retail;

use App\Enums\DiscountSale;
use App\Enums\OptionType;
use App\Models\RetailProduct;
use App\Services\RetailService;
use App\Utils\Code;
use App\Utils\Common;

class ProductInfo
{
    public int $no_product;
    public int $no_partner;
    public int $no_category;
    public ?int $no_sub_category;
    public string $nm_product;
    public string $ds_content;
    public ?float $at_price_before;
    public ?float $at_price;
    public ?string $ds_image_path;
    public ?string $ds_detail_image_path;
    public ?string $cd_discount_sale;
    public string $yn_option;
    public string $yn_new;
    public string $yn_soldout;
    public string $yn_part_soldout;
    public int $cnt_product;
    public string $policy_uri;
    public object $product_option_groups;
    public ?object $two_plus_one_option;

    public function __construct(RetailProduct $product, ?array $realProductStock)
    {
        $stock = RetailService::getOptionMinStock($product->productOptionGroups, $realProductStock);

        $this->no_product = $product->no_product;
        $this->no_partner = $product->no_partner;
        $this->no_category = $product->no_category;
        $this->no_sub_category = $product->no_sub_category;
        $this->nm_product = $product->nm_product;
        $this->ds_content = $product->ds_content;
        $this->at_price_before = $product->at_price_before;
        $this->at_price = $product->at_price;
        $this->ds_image_path = Common::getImagePath($product->ds_image_path);
        $this->ds_detail_image_path = Common::getImagePath($product->ds_detail_image_path);
        $this->cd_discount_sale = match (false) {
            empty($product->cd_discount_sale) => DiscountSale::case($product->cd_discount_sale)?->name,
            default => null
        };
        $this->yn_option = $product->yn_option;
        $this->yn_new = $product->yn_new;
        $this->cnt_product = match ($product->productOptionGroups?->where(
            'cd_option_type',
            OptionType::REQUIRED->value
        )->count()) {
            0 => data_get($realProductStock, $product->no_barcode),
            default => $stock['require']
        };
        $this->yn_soldout = $this->cnt_product > 0 ? 'N' : 'Y';
        $this->yn_part_soldout = match ($product->productOptionGroups?->where(
            'cd_option_type',
            OptionType::SELECT->value
        )->count()) {
            0 => 'N',
            default => $stock['select'] > 0 ? 'N' : 'Y'
        };
        $this->policy_uri = Code::conf('policy_uri');
        $this->product_option_groups = $product->productOptionGroups->map(function ($group) use ($realProductStock) {
            return (new ProductOptiongroup($group, $realProductStock))->setProductOptionGroup();
        });
        $this->two_plus_one_option = match ($product->cd_discount_sale == DiscountSale::TWO_PLUS_ONE->value) {
            true => collect([
                [
                    'nm_product' => sprintf('%s %s', $product->nm_product, Code::conf('two_plus_one.single')),
                    'discount_type' => 'SINGLE',
                    'at_price' => $product->at_price
                ]
            ])->push([
                'nm_product' => sprintf('%s %s', $product->nm_product, Code::conf('two_plus_one.double')),
                'discount_type' => 'DOUBLE',
                'at_price' => $product->at_price * 2
            ])->values(),
            default => null
        };
    }

    public function setProductInfo(): ProductInfo
    {
        return $this;
    }

}
