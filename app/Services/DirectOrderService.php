<?php

namespace App\Services;

use App\Enums\DiscountSale;
use App\Enums\SearchBizKind;
use App\Models\DirectOrderList;
use Illuminate\Support\Collection;

class DirectOrderService extends Service
{
    public static function get(array $parameter): Collection
    {
        return DirectOrderList::where($parameter)
            ->with([
                'orderList.shop.partner',
                'orderList.retailOrderProduct.retailProduct',
                'orderList.retailOrderProduct.retailOrderProductOption.retailProductOption',
                'orderList.orderProduct.product.partnerCategory',
                'orderList.orderProduct.product.productOptionGroups.productOptions',
                'parkingOrderList.parkingSite'
            ])
            ->orderByDesc('dt_reg')
            ->get()->map(function ($collect) {
                $bizKind = SearchBizKind::getBizKind($collect->cd_biz_kind);
                $orderProducts = match ($bizKind) {
                    SearchBizKind::FNB => $collect->orderList->orderProduct,
                    SearchBizKind::RETAIL => $collect->orderList->retailOrderProduct,
                    default => null
                };

                return [
                    'no' => $collect->no,
                    'cd_biz_kind' => $collect->cd_biz_kind,
                    'biz_kind' => $bizKind->name,
                    'no_site' => $collect->parkingOrderList?->no_site,
                    'no_shop' => $collect->orderList?->no_shop,
                    'at_price_total' => match ($bizKind) {
                        SearchBizKind::PARKING => $collect->parkingOrderList->at_price_pg,
                        default => ($collect->orderList->at_price_pg + $collect->orderList->at_cpn_disct) - ($collect->orderList->at_commission_rate + $collect->orderList->at_send_price)
                    },
                    'nm_order' => match ($bizKind) {
                        SearchBizKind::PARKING => $collect->parkingOrderList->nm_order,
                        default => $collect->orderList->nm_order
                    },
                    'nm_shop' => match ($bizKind) {
                        SearchBizKind::PARKING => $collect->parkingOrderList->parkingSite->nm_shop,
                        default => sprintf('%s %s', $collect->orderList->shop->partner->nm_partner, $collect->orderList->shop->nm_shop)
                    },
                    'list_product' => match ($bizKind) {
                        SearchBizKind::RETAIL, SearchBizKind::FNB => OrderService::makeListProduct($collect->orderList)->map(function ($product) use ($bizKind, $orderProducts) {
                            $orderProduct = $orderProducts->firstWhere('no_product', $product['no_product']);

                            return [
                                'no_product' => $product['no_product'],
                                'category' => $orderProduct->product?->partnerCategory->no_partner_category ?? $orderProduct->retailProduct?->no_category,
                                'ea' => $product['ct_inven'],
                                'discount_type' => $orderProduct->cd_discount_sale ? DiscountSale::case($orderProduct->cd_discount_sale)?->name : null,
                                'at_price' => $product['at_price_product'],
                                'is_buy' => match ($bizKind) {
                                    SearchBizKind::FNB => empty($orderProduct->product) === false && $orderProduct->product->ds_status == 'Y',
                                    SearchBizKind::RETAIL => empty($orderProduct->retailProduct) === false && $orderProduct->retailProduct->yn_show == 'Y',
                                    default => true
                                },
                                'option' => match (gettype($product['option'])) {
                                    'object' => collect($product['option'])->map(function ($option) use ($bizKind, $orderProduct) {
                                        return [
                                            'no_option_group' => $option['no_option_group'],
                                            'no_option' => $option['no_option'],
                                            'add_price' => $option['add_price'],
                                            'is_buy' => match ($bizKind) {
                                                SearchBizKind::FNB => empty($orderProduct->product?->productOptionGroups->firstWhere('no_group', $option['no_option_group'])->productOptions->firstWhere('no_option', $option['no_option'])) === false,
                                                SearchBizKind::RETAIL => empty($orderProduct->retailOrderProductOption->firstWhere('no_option', $option['no_option'])->retailProductOption) === false,
                                                default => false
                                            }
                                        ];
                                    }),
                                    'boolean' => [],
                                    default => $product['option']
                                }
                            ];
                        }),
                        default => null
                    }
                ];
            });
    }

    public static function create(int $noUser, string $noOrder, string $cdBizKind): void
    {
        (new DirectOrderList([
            'no_user' => $noUser,
            'cd_biz_kind' => $cdBizKind,
            'no_order' => $noOrder,
        ]))->saveOrFail();
    }

    public static function remove(int $noUser, int $no)
    {
        DirectOrderList::where([
            'no' => $no,
            'no_user' => $noUser,
        ])->delete();
    }

    public static function hasDirectOrder(array $parameter): bool
    {
        return DirectOrderList::where($parameter)->count() <= 0;
    }
}