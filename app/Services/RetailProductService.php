<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\RetailCategory;
use App\Models\RetailProduct;
use App\Models\RetailProductBestLabel;
use App\Utils\Common;
use Illuminate\Support\Facades\DB;

class RetailProductService
{
    public static function getRetailProductBestLabel($noPartner)
    {
        return RetailProductBestLabel::where([
            'no_partner' => $noPartner,
            'ds_status' => 'Y'
        ])->orderByDesc('no')->first();
    }

    public static function getRetailProductBest($noPartner, $offset, $size)
    {
        $retailProductBestLabel = RetailProductBestLabel::where([
            'no_partner' => $noPartner,
            'ds_status' => 'Y'
        ])->orderByDesc('no')->first();

        //todo 테스트 필요 (retail_product_best에 데이터가 없음)
        return RetailProduct::select([
            DB::raw('retail_product.*'),
            DB::raw("0 AS cnt_product"),
            DB::raw("'' AS yn_soldout"),
        ])->join('retail_product_best', function ($q) {
            $q->on('retail_product.no_product', 'retail_product_best.no_product');
            $q->on('retail_product.no_partner', 'retail_product_best.no_partner');
        })->with([
            'productOptionGroups.productOptionProducts'
        ])->where([
            ['retail_product.no_partner', '=', $noPartner],
            ['retail_product.ds_status', '=', 'Y'],
            ['retail_product.yn_show', '=', 'Y'],
            ['retail_product.dt_sale_st', '<=', DB::raw("CURRENT_TIMESTAMP()")],
            ['retail_product.dt_sale_end', '>=', DB::raw("CURRENT_TIMESTAMP()")],
        ])->orderBy('retail_product_best.at_view')->take($size)->offset($offset)->get()->map(
            function ($value) use ($retailProductBestLabel) {
                if ($retailProductBestLabel['ds_label']) {
                    $value['ds_label'] = $retailProductBestLabel['ds_label'];
                }
                return $value;
            }
        );
    }

    public static function getRetailCategory($noPartner, $noCategory = null, $noSubCategory = null)
    {
        $where = [
            ['no_partner', '=', $noPartner],
            ['ds_status', '=', 'Y'],
            ['yn_show', '=', 'Y'],
        ];

        $retailCategory = RetailCategory::with(['retailSubCategories']);
        if ($noCategory) {
            $where[] = ['no_category', '=', $noCategory];
        } else {
            $where[] = ['dt_use_st', '<=', DB::raw("CURRENT_TIMESTAMP()")];
            $where[] = ['dt_use_end', '>=', DB::raw("CURRENT_TIMESTAMP()")];
        }

        if ($noSubCategory) {
            $retailCategory = $retailCategory->with([
                'retailSubCategories' => function ($q) use ($noSubCategory) {
                    $q->select([
                        'no_category',
                        'no_sub_category',
                        'nm_sub_category'
                    ]);
                    $q->where('no_sub_category', $noSubCategory);
                }
            ]);
        }

        return $retailCategory->where($where)->orderByDesc('yn_top')->orderBy('at_view')->orderBy(
            'no_category'
        )->orderBy('at_view');
    }

    public static function getRetailProduct(
        $noPartner,
        $noShop,
        $noCategory,
        $noSubCategory,
        $size,
        $ctPage,
        $isPackage = false
    ) {
        $where = [
            ['retail_product.ds_status', '=', 'Y'],
            ['retail_product.yn_show', '=', 'Y'],
//            ['retail_product.dt_sale_st', '<=', DB::raw("CURRENT_TIMESTAMP()")],
//            ['retail_product.dt_sale_end', '>=', DB::raw("CURRENT_TIMESTAMP()")]
        ];

        $select = [
            'retail_product.*',
        ];

        if ($noPartner) {
            $where[] = ['retail_product.no_partner', '=', $noPartner];
        }

        if ($noCategory && !$isPackage) {
            $where[] = ['retail_product.no_category', '=', $noCategory];
        }

        if ($noSubCategory) {
            $where[] = ['retail_product.no_sub_category', '=', $noSubCategory];
        }

        if ($ctPage) {
            $where[] = ['retail_product.no', '>', $ctPage];
        }

        if ($isPackage) {
            $where[] = ['retail_product.no_category', '=', "{$noPartner}9999"];
        }

        $retailProduct = RetailProduct::where($where)->whereBetween(
            DB::raw('now()'),
            [DB::raw('retail_product.dt_sale_st'), DB::raw('retail_product.dt_sale_end')]
        );

        if ($noShop) {
            $select[] = 'retail_shop_product_stock.cnt_product';
            $select[] = 'retail_shop_product_stock.yn_soldout';

            $retailProduct = $retailProduct->leftJoin('retail_shop_product_stock', function ($q) use ($noShop) {
                $q->on('retail_product.no_product', 'retail_shop_product_stock.no_product');
                $q->where('no_shop', $noShop);
            });
        }

        if ($size) {
            $retailProduct = $retailProduct->take($size);
        }

        return $retailProduct->select($select)->with([
            'productOptionGroups.productOptionProducts',
        ])->orderBy('at_view')->get();
    }

    public static function getSearchProduct($noPartner, $searchWord)
    {
        return RetailProduct::select([
            'no_product',
            'nm_product',
            DB::raw("POSITION('{$searchWord}' IN nm_product) AS pos")
        ])->where([
            ['no_partner', '=', $noPartner],
            ['nm_product', 'LIKE', "%{$searchWord}%"],
            ['ds_status', '=', 'Y'],
            ['yn_show', '=', 'Y'],
            ['dt_sale_st', '<=', DB::raw("CURRENT_TIMESTAMP()")],
            ['dt_sale_end', '>=', DB::raw("CURRENT_TIMESTAMP()")],
        ])->orderByRaw("POSITION('{$searchWord}' IN nm_product) ASC")->get();
    }

    public static function getRetailProductInfo($noPartner, $noShop, $noProduct): ?RetailProduct
    {
        return RetailProduct::select([
            'retail_product.*',
            'retail_shop_product_stock.cnt_product',
            'retail_shop_product_stock.yn_soldout',
        ])->leftJoin('retail_shop_product_stock', function ($q) use ($noShop) {
            $q->on('retail_product.no_product', 'retail_shop_product_stock.no_product');
            $q->where('retail_shop_product_stock.no_shop', $noShop);
        })->where([
            ['retail_product.no_partner', '=', $noPartner],
            ['retail_product.no_product', '=', $noProduct],
        ])->with([
            'productOptionGroups.productOptionProducts',
        ])->get()->map(function ($product) {
            $product->ds_image_path = match (!$product->ds_image_path || !file_exists($product->ds_image_path)) {
                true => Common::getImagePath("/data2/partner/retail_default.jpg"),
                default => Common::getImagePath($product->ds_image_path)
            };

            return $product;
        })->first();
    }

    public static function getRetailProductIds($products): object
    {
        return $products->map(function ($product) {
            return $product->productOptionGroups?->map(function ($group) {
                return $group->productOptionProducts?->map(function ($option) {
                    return $option->no_barcode_opt;
                })->values();
            })->collect()->merge($product->no_barcode);
        })->flatten();
    }
}
