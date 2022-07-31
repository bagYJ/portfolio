<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PartnerCategory;
use App\Models\Product;
use App\Models\ProductIgnore;
use App\Models\SearchLog;
use App\Utils\Code;
use App\Utils\Common;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductService extends Service
{
    /**
     * 브랜드 카테고리 조회
     * @param int $noPartner
     * @return Collection
     */
    public static function getCategory(int $noPartner): Collection
    {
        return PartnerCategory::select([
            DB::raw("no_partner_category AS no_category"),
            'nm_category',
        ])->where([
            ['no_partner', '=', $noPartner],
            ['no_partner_category', '!=', "{$noPartner}9999"],
        ])->orderBy('ct_order')->get();
    }

    /**
     * 상품 리스트 조회
     * @param int $noPartner
     * @param int $noShop
     * @param int|null $noCategory
     * @param string|null $cdBizKind
     * @return array
     */
    public static function gets(int $noPartner, int $noShop, int $noCategory = null, string $cdBizKind = null): array
    {
        $where = [
            ['no_partner', '=', $noPartner],
            ['ds_status', '=', 'Y'],
            ['no_partner_category', '!=', "{$noPartner}9999"],
        ];

        if ($noCategory) {
            $where['no_partner_category'] = $noCategory;
        }

        $products = Product::where($where)->whereNotIn('no_product', function ($query) use ($noShop) {
            $query->select('no_product')->from('product_ignore')->where('no_shop', $noShop);
        })->with(['productOptionGroups.productOptions', 'partner'])
            ->orderBy('at_view_order')->orderBy('nm_product')->get();

        return [
            'product_count' => $products->count(),
            'ds_bi' => $products->first()?->partner->ds_bi,
            'image_path' => Code::conf('image_path'),
            'products' => $products->map(function ($collect) {
                return [
                    'no_product' => $collect->no_product,
                    'ds_option_sel' => $collect->ds_option_sel,
                    'nm_product' => $collect->nm_product,
                    'ds_content' => $collect->ds_content,
                    'no_partner_category' => $collect->no_partner_category,
                    'at_price_before' => $collect->at_price_before,
                    'at_price' => $collect->at_price,
                    'ds_image_path' => $collect->ds_image_path ? Common::getImagePath($collect->ds_image_path) : null,
                    'yn_new' => $collect->yn_new,
                    'yn_vote' => $collect->yn_vote,
                    'at_view_order' => $collect->at_view_order,
                    'at_ratio' => Common::getSaleRatio($collect->at_price_before, $collect->at_price),
                    'option_groups' => empty($collect->option_group) == false ? $collect->productOptionGroups->whereIn(
                        'no_group',
                        json_decode($collect->option_group)
                    )->map(function ($optionGroup) {
                        return [
                            'no_group' => $optionGroup->no_group,
                            'nm_group' => $optionGroup->nm_group,
                            'min_option_select' => $optionGroup->min_option_select ?? 0,
                            'max_option_select' => $optionGroup->max_option_select ?? 0,
                            'option_type' => match ($optionGroup->min_option_select == 1 && empty($optionGroup->max_option_select) === true) {
                                true => 'radio',
                                default => 'checkbox'
                            },
                            'product_options' => $optionGroup->productOptions->map(function ($option) {
                                return [
                                    'no_option' => $option->no_option,
                                    'nm_option' => $option->nm_option,
                                    'at_add_price' => $option->at_add_price,
                                ];
                            })->values()
                        ];
                    })->values() : []
                ];
            })
        ];
    }

    public static function createSearchLog($noShop, $searchWord, $noUser = null)
    {
        return SearchLog::create([
            'no_shop' => $noShop,
            'no_user' => $noUser,
            'search_word' => $searchWord,
            'ref_week' => DB::raw("DAYOFWEEK(NOW())"),
        ]);
    }

    public static function getProduct(array $parameter, ?array $whereIn = [], ?int $excludeShop = null): Collection
    {
        return Product::where($parameter)->when(empty($whereIn) === false, function ($query) use ($whereIn) {
            foreach ($whereIn as $key => $value) {
                $query->whereIn($key, $value);
            }
        })->when($excludeShop, function ($query) use ($excludeShop) {
            $query->whereNotIn('no_product', self::getProductIgnore($excludeShop));
        })->with(['productOptionGroups.productOptions'])->get()->map(function ($collect) {
            return [
                'no_product' => $collect->no_product,
                'ds_option_sel' => $collect->ds_option_sel,
                'nm_product' => $collect->nm_product,
                'ds_content' => $collect->ds_content,
                'no_partner_category' => $collect->no_partner_category,
                'at_price_before' => $collect->at_price_before,
                'at_price' => $collect->at_price,
                'ds_image_path' => $collect->ds_image_path ? Common::getImagePath($collect->ds_image_path) : null,
                'yn_new' => $collect->yn_new,
                'yn_vote' => $collect->yn_vote,
                'at_view_order' => $collect->at_view_order,
                'at_ratio' => Common::getSaleRatio($collect->at_price_before, $collect->at_price),
                'policy_uri' => Code::conf('policy_uri'),
                'option_groups' => $collect->productOptionGroups->whereIn(
                    'no_group',
                    json_decode($collect->option_group)
                )->map(function ($optionGroup) {
                    return [
                        'no_group' => $optionGroup->no_group,
                        'nm_group' => $optionGroup->nm_group,
                        'min_option_select' => $optionGroup->min_option_select ?? 0,
                        'max_option_select' => $optionGroup->max_option_select ?? 0,
                        'option_type' => match ($optionGroup->min_option_select == 1 && empty($optionGroup->max_option_select) === true) {
                            true => 'radio',
                            default => 'checkbox'
                        },
                        'product_options' => $optionGroup->productOptions->map(function ($option) {
                            return [
                                'no_option' => $option->no_option,
                                'nm_option' => $option->nm_option,
                                'at_add_price' => $option->at_add_price,
                            ];
                        })->values()
                    ];
                })->values()
            ];
        });
    }

    public static function getProductIgnore(int $noShop): Collection
    {
        return ProductIgnore::where('no_shop', $noShop)->pluck('no_product');
    }
}
