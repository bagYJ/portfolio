<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AppType;
use App\Enums\EnumYN;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Shop;
use App\Utils\Common;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchService extends Service
{
    public function getRadiusSingleRound(
        float $distance,
        array $positions,
        ?int $noPartner = null,
        ?array $cdBizKind = [],
        ?array $cdBizKindDetail = [],
        ?string $cdThirdParty = null
    ): Collection {
        return Shop::with([
            'shopOilPrice',
            'product' => function ($query) {
                $query->select('product.*');
                $query->leftJoin('product_ignore', 'product_ignore.no_product', 'product.no_product');
                $query->whereNull('product_ignore.no_product');
            }
        ])->leftJoin('partner AS p', 'shop.no_partner', '=', 'p.no_partner')
            ->leftJoin('shop_detail AS sd', 'shop.no_shop', '=', 'sd.no_shop')
            ->leftJoin('shop_opt_time AS sot', function ($query) {
                $query->on('shop.no_shop', '=', 'sot.no_shop')
                    ->on('sot.nt_weekday', '=', DB::raw('WEEKDAY(NOW())'))
                    ->whereBetween(
                        DB::raw(now()->format('Hi')),
                        [DB::raw('sot.ds_open_time'), DB::raw('sot.ds_close_time')]
                    );
            })->leftJoin('shop_holiday AS sh', function ($query) {
                $query->on('shop.no_shop', '=', 'sh.no_shop')
                    ->on(
                        'sh.no',
                        '=',
                        DB::raw(
                            sprintf(
                                '
                    (SELECT
                        no
                    FROM
                        shop_holiday AS sh
                    WHERE
                        sh.no_shop = shop.no_shop
                        AND nt_weekday = WEEKDAY(NOW())
                        AND (
                            cd_holiday = ?
                            OR (cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(%1$s) = 0)
                            OR (cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(%1$s) = 1)
                            OR (cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(%1$s) = 2)
                            OR (cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(%1$s) = 3))
                            OR (cd_holiday = ? AND NOW() BETWEEN dt_imsi_start AND dt_imsi_end
                        ) LIMIT 1)',
                                now()->startOfMonth()->format('Y-m-d')
                            )
                        )
                    )->setBindings([
                        '211200',
                        '211300',
                        '211400',
                        '211500',
                        '211600',
                        '211900'
                    ]);
            })->where([
                'shop.yn_display_map' => EnumYN::Y->name,
                'sd.cd_contract_status' => '207100'
            ])->where(
                function ($query) use ($distance, $positions, $noPartner, $cdBizKind, $cdBizKindDetail, $cdThirdParty) {
                    foreach ($positions as $position) {
                        $query->orWhere(
                            DB::raw(
                                sprintf(
                                    '(6371 * ACOS(COS(RADIANS(%1$s)) * COS(RADIANS(at_lat)) * COS(RADIANS(at_lng) -RADIANS(%2$s)) + SIN(RADIANS(%1$s)) * SIN(RADIANS(at_lat))))',
                                    $position['x'],
                                    $position['y']
                                )
                            ),
                            '<=',
                            $distance
                        );
                    }
                    if (empty($noPartner) === false) {
                        $query->where('p.no_partner', $noPartner);
                    }
                    if (empty($cdBizKind) === false) {
                        $query->whereIn('p.cd_biz_kind', $cdBizKind);
                    }
                    if (empty($cdBizKindDetail) === false) {
                        $query->whereIn('p.cd_biz_kind_detail', $cdBizKindDetail);
                    }
                    if (empty($cdThirdParty) === false) {
                        $query->where('shop.list_cd_third_party', 'REGEXP', $cdThirdParty);
                    }
                }
            )
            ->whereRaw(
                'IF(? BETWEEN sot.ds_break_start_time AND sot.ds_break_end_time, true, false) = false',
                [now()->format('Hi')]
            )
            ->whereRaw(
                'IF(? BETWEEN sot.ds_break_start_time2 AND sot.ds_break_end_time2, true, false) = false',
                [now()->format('Hi')]
            )
            ->whereRaw(
                'IF(sh.nt_weekday = WEEKDAY(NOW()) OR (NOW() BETWEEN sh.dt_imsi_start AND sh.dt_imsi_start), true, false) = false'
            )
            ->select([
                '*',
                DB::raw("CONCAT(p.nm_partner, ' ', shop.nm_shop) AS nm_shop"),
                DB::raw('shop.no_shop AS no_shop'),
                DB::raw(
                    sprintf(
                        '(6371 * ACOS(COS(RADIANS(%1$s)) * COS(RADIANS(at_lat)) * COS(RADIANS(at_lng) -RADIANS(%2$s)) + SIN(RADIANS(%1$s)) * SIN(RADIANS(at_lat)))) as distance',
                        $positions[0]['x'],
                        $positions[0]['y']
                    )
                )
            ])->orderBy('distance')->get();
    }

    public static function getPosError($noShop)
    {
        $sub = Shop::select([
            'no_shop_ark',
            'yn_control',
            'cd_ark_status',
            'shop.no_shop',
            DB::raw("(TIMESTAMPDIFF(MINUTE, ark.dt_upt, NOW())) AS alert_time_diff"),
            'yn_control_ark'
        ])->join('ark', 'shop.no_shop', '=', 'ark.no_shop')
            ->leftJoin('shop_opt_time', function ($q) {
                $q->on('shop.no_shop', 'shop_opt_time.no_shop');
                $q->where('shop_opt_time.nt_weekday', DB::raw('WEEKDAY(CURDATE())'));
            })->leftJoin('shop_holiday', function ($q) {
                $q->on('shop_holiday.no_shop', 'shop.no_shop');
                $q->whereRaw(
                    "( (cd_holiday = 211900 AND dt_imsi_start <= NOW() AND dt_imsi_end >= NOW() )
							OR ( cd_holiday = 211200 AND shop_holiday.nt_weekday = WEEKDAY(CURDATE()))
							OR ( cd_holiday = 211300 AND shop_holiday.nt_weekday = WEEKDAY(CURDATE()) AND CAST(DAYOFMONTH(NOW())/7 AS UNSIGNED INTEGER)=0 ) -- 매주첫째주
							OR ( cd_holiday = 211400 AND shop_holiday.nt_weekday = WEEKDAY(CURDATE()) AND CAST(DAYOFMONTH(NOW())/7 AS UNSIGNED INTEGER)=1 ) -- 매주두째주
							OR ( cd_holiday = 211500 AND shop_holiday.nt_weekday = WEEKDAY(CURDATE()) AND CAST(DAYOFMONTH(NOW())/7 AS UNSIGNED INTEGER)=2 ) -- 매주세째주
							OR ( cd_holiday = 211600 AND shop_holiday.nt_weekday = WEEKDAY(CURDATE()) AND CAST(DAYOFMONTH(NOW())/7 AS UNSIGNED INTEGER)=3 ) -- 매주네째주
							)"
                );
            })->whereRaw(
                "((ark.cd_ark_status = '304900')
            OR ( ark.cd_ark_status = '304200' AND ark.no_shop_ark < '99' AND ark.dt_upt < DATE_ADD(NOW(), INTERVAL -35 MINUTE)))"
            )->whereRaw(
                "shop.ds_status = 'Y' AND DATE_FORMAT(NOW(), '%H%i') BETWEEN shop_opt_time.ds_open_time AND shop_opt_time.ds_close_time"
            )
            ->groupBy([
                'ark.no_shop_ark',
                'ark.yn_control',
                'shop.no_shop',
                'ark.dt_upt',
                'ark.yn_control_ark'
            ]);

        return DB::table(DB::raw("({$sub->toSql()}) as sub"))
            ->mergeBindings($sub->getQuery())->where([
                ['no_shop', '=', $noShop],
                ['no_shop_ark', '>', 30],
                ['alert_time_diff', '>', 4]
            ])->orderBy('no_shop_ark')->first();
    }

    public function homeProductList(float $radius, array $position, ?int $limit = 5): Collection
    {
        $now = now()->format('Hi');
        return Product::join('partner AS p', 'p.no_partner', '=', 'product.no_partner')
            ->join('shop', 'shop.no_partner', '=', 'product.no_partner')
            ->leftJoin('product_ignore', 'product_ignore.no_product', '=', 'product.no_product')
            ->leftJoin('shop_opt_time AS sot', function ($query) use ($now) {
                $query->on('shop.no_shop', '=', 'sot.no_shop')
                    ->on('sot.nt_weekday', '=', DB::raw('WEEKDAY(NOW())'))
                    ->whereBetween(DB::raw($now), [DB::raw('sot.ds_open_time'), DB::raw('sot.ds_close_time')]);
            })
            ->leftJoin('shop_holiday AS sh', function ($query) {
                $query->on('shop.no_shop', '=', 'sh.no_shop')
                    ->on(
                        'sh.no',
                        '=',
                        DB::raw(
                            sprintf(
                                '
                    (SELECT
                        no
                    FROM
                        shop_holiday AS sh
                    WHERE
                        sh.no_shop = shop.no_shop
                        AND nt_weekday = WEEKDAY(NOW())
                        AND (
                            cd_holiday = ?
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 0)
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 1)
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 2)
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 3))
                            OR (cd_holiday = ? AND NOW() BETWEEN dt_imsi_start AND dt_imsi_end
                        ) LIMIT 1)',
                                now()->format('Y-m-d'),
                                now()->startOfMonth()->format('Y-m-d')
                            )
                        )
                    )->setBindings([
                        '211200',
                        '211300',
                        '211400',
                        '211500',
                        '211600',
                        '211900'
                    ]);
            })
            ->whereNull('product_ignore.no_product')
            ->select([
                DB::raw("CONCAT(p.nm_partner, ' ',  shop.nm_shop) AS nm_shop"),
                'shop.no_shop',
                'p.no_partner',
                DB::raw(
                    sprintf(
                        '(6371 * ACOS(COS(RADIANS(%1$s)) * COS(RADIANS(shop.at_lat)) * COS(RADIANS(shop.at_lng) -RADIANS(%2$s)) + SIN(RADIANS(%1$s)) * SIN(RADIANS(shop.at_lat)))) AS distance',
                        $position['x'],
                        $position['y']
                    )
                ),
                'product.no_product',
                'nm_product',
                'at_price_before',
                'at_price',
                'ds_image_path',
                'ds_recommend_start_time',
                'ds_recommend_end_time',
            ])->where('shop.at_lat', '>', 0)
            ->where('shop.at_lng', '>', 0)
//            ->whereRaw('IF(? BETWEEN product.ds_recommend_start_time AND product.ds_recommend_end_time, true, false) = true', [$now])
            ->whereRaw('IF(? BETWEEN sot.ds_break_start_time AND sot.ds_break_end_time, true, false) = false', [$now])
            ->whereRaw('IF(? BETWEEN sot.ds_break_start_time2 AND sot.ds_break_end_time2, true, false) = false', [$now])
            ->whereRaw(
                'IF(sh.nt_weekday = WEEKDAY(NOW()) OR (NOW() BETWEEN sh.dt_imsi_start AND sh.dt_imsi_start), true, false) = false'
            )
            ->whereIn('p.cd_biz_kind', ['201100', '201200', '201400'])
            ->groupBy(['p.no_partner', 'shop.no_shop'])
            ->having('distance', '<=', $radius)
            ->orderByRaw(
                "IF({$now} BETWEEN product.ds_recommend_start_time AND product.ds_recommend_end_time, true, false) = true DESC"
            )
            ->orderBy('distance')
            ->limit($limit)->get()->map(function ($item) {
                $item->at_ratio = Common::getSaleRatio($item->at_price_before, $item->at_price);
                return $item;
            });
    }

    public function homeShopList(float $radius, array $position, ?int $limit = 5, ?int $productLimit = 5): Collection
    {
        return Shop::with([
            'product' => function ($query) {
                $query->select('product.*')
                    ->leftJoin('product_ignore', 'product_ignore.no_product', 'product.no_product')
                    ->whereNull('product_ignore.no_product');

                match (getAppType()) {
                    AppType::AVN => $query->where('no_partner_category', 'LIKE', '%9999'),
                    default => $query->where('no_partner_category', 'NOT LIKE', '%9999')
                };
            }
        ])->has('product', '>', 4)
            ->join('partner AS p', 'shop.no_partner', '=', 'p.no_partner')
            ->leftJoin('shop_opt_time AS sot', function ($query) {
                $query->on('shop.no_shop', '=', 'sot.no_shop')
                    ->on('sot.nt_weekday', '=', DB::raw('WEEKDAY(NOW())'))
                    ->whereBetween(
                        DB::raw(now()->format('Hi')),
                        [DB::raw('sot.ds_open_time'), DB::raw('sot.ds_close_time')]
                    );
            })->leftJoin('shop_holiday AS sh', function ($query) {
                $query->on('shop.no_shop', '=', 'sh.no_shop')
                    ->on(
                        'sh.no',
                        '=',
                        DB::raw(
                            sprintf(
                                '
                    (SELECT
                        no
                    FROM
                        shop_holiday AS sh
                    WHERE
                        sh.no_shop = shop.no_shop
                        AND nt_weekday = WEEKDAY(NOW())
                        AND (
                            cd_holiday = ?
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 0)
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 1)
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 2)
                            OR (cd_holiday = ? AND WEEKOFYEAR(%1$s) - WEEKOFYEAR(%2$s) = 3))
                            OR (cd_holiday = ? AND NOW() BETWEEN dt_imsi_start AND dt_imsi_end
                        ) LIMIT 1)',
                                now()->format('Y-m-d'),
                                now()->startOfMonth()->format('Y-m-d')
                            )
                        )
                    )->setBindings([
                        '211200',
                        '211300',
                        '211400',
                        '211500',
                        '211600',
                        '211900'
                    ]);
            })->select([
                DB::raw("CONCAT(p.nm_partner, ' ',  shop.nm_shop) AS nm_shop"),
//            'shop.nm_shop',
                'shop.no_shop',
//            'shop.no_partner',
//            'p.nm_partner',
                'p.no_partner',
                DB::raw(
                    sprintf(
                        '(6371 * ACOS(COS(RADIANS(%1$s)) * COS(RADIANS(shop.at_lat)) * COS(RADIANS(shop.at_lng) -RADIANS(%2$s)) + SIN(RADIANS(%1$s)) * SIN(RADIANS(shop.at_lat)))) AS distance',
                        $position['x'],
                        $position['y']
                    )
                )
            ])->where('shop.at_lat', '>', 0)
            ->where('shop.at_lng', '>', 0)
            ->whereRaw(
                'IF(? BETWEEN sot.ds_break_start_time AND sot.ds_break_end_time, true, false) = false',
                [now()->format('Hi')]
            )
            ->whereRaw(
                'IF(? BETWEEN sot.ds_break_start_time2 AND sot.ds_break_end_time2, true, false) = false',
                [now()->format('Hi')]
            )
            ->whereRaw(
                'IF(sh.nt_weekday = WEEKDAY(NOW()) OR (NOW() BETWEEN sh.dt_imsi_start AND sh.dt_imsi_start), true, false) = false'
            )
            ->whereIn('p.cd_biz_kind', ['201100', '201200', '201400']) //다른 카테고리 추가 시에 프론트와 이야기 해보아야 함!!
            //->having('distance', '<=', $radius) //매장이 안나오는 경우가 있어 일단 주석처리
            ->orderBy('distance')->limit($limit)->get();
    }

    public static function getTags(): array
    {
        return Partner::select(
            DB::raw("CONCAT('[\"', REPLACE(GROUP_CONCAT(tags SEPARATOR '|'), '|', '\",\"'), '\"]') AS tag")
        )->get()
            ->map(function ($tags) {
                return array_values(array_unique(array_filter(json_decode($tags->tag))));
            })->first();
    }
}
