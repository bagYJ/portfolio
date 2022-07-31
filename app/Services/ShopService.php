<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AppType;
use App\Enums\SearchBizKind;
use App\Exceptions\OwinException;
use App\Exceptions\TMapException;
use App\Models\Shop;
use App\Models\ShopHoliday;
use App\Models\ShopOilPrice;
use App\Models\ShopOilUnuseCard;
use App\Models\ShopOptTime;
use App\Utils\Code;
use App\Utils\Common;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ShopService extends Service
{
    /**
     * 매장 휴일정보 반환
     *
     * @param int $noShop
     * @return array
     */
    public static function getShopHoliday(int $noShop): array
    {
        $timestamp = date('Y-m-d H:i:s');
        $today = intval(date('N') - 1);
        $searchEndTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+1 minutes'));

        $response = [
            'regular' => [],
            'temp' => [],
        ];
        $shopHolidays = ShopHoliday::where([
            ['no_shop', '=', $noShop],
            ['cd_holiday', '<>', 211900],
        ])->orWhere([
            ['no_shop', '=', $noShop],
            ['cd_holiday', '=', 211900],
            ['dt_imsi_end', '>', $searchEndTime],
        ])->get();

        foreach ($shopHolidays as $holiday) {
            //정기 휴일
            if (in_array($holiday['cd_holiday'], [211200, 211300, 211400, 211500, 211600])) {
                if (!data_get($response, 'regular.' . $holiday['cd_holiday'])) {
                    $response['regular'][$holiday['cd_holiday']] = [];
                }
                $response['regular'][$holiday['cd_holiday']][] = $holiday['nt_weekday'];

                $response['yn_open'] = match ($holiday['cd_holiday']) {
                    '211200' => $today === $holiday['nt_weekday'] ? 'Y' : null,
                    '211300' => Common::getWeekByMonth(
                        $timestamp
                    ) == 1 && $today === $holiday['nt_weekday'] ? 'Y' : null,
                    '211400' => Common::getWeekByMonth(
                        $timestamp
                    ) == 2 && $today === $holiday['nt_weekday'] ? 'Y' : null,
                    '211500' => Common::getWeekByMonth(
                        $timestamp
                    ) == 3 && $today === $holiday['nt_weekday'] ? 'Y' : null,
                    '211600' => Common::getWeekByMonth(
                        $timestamp
                    ) == 4 && $today === $holiday['nt_weekday'] ? 'Y' : null,
                    default => null,
                };
            }

            //임시 휴일
            if ($holiday['dt_imsi_start'] && $holiday['dt_imsi_end'] && !isset($holiday['nt_weekday'])) {
                $response['temp'][] = $holiday;
                $startTime = date('Y-m-d H:i:s', strtotime($holiday['dt_imsi_start'] . '-1 minutes'));
                $endTime = date('Y-m-d H:i:s', strtotime($holiday['dt_imsi_end'] . '+1 minutes'));
                if ($timestamp > $startTime && $timestamp < $endTime) {
                    $response['yn_open'] = 'T'; // 매장임시휴일 [T]
                }
            }
        }
        $response['yn_open'] = $response['yn_open'] ?? 'Y';

        return $response;
    }

    /**
     * 매장 오늘 운영시간 정보 반환
     * @param int $noShop
     * @return ShopOptTime|null
     */
    public static function getInfoOptTime(int $noShop): ?ShopOptTime
    {
        return ShopOptTime::where([
            'no_shop' => $noShop,
            'nt_weekday' => DB::raw("WEEKDAY(CURDATE())")
        ])->first();
    }

    public static function getYnShopOpen(Shop $shopInfo): string
    {
        $ynOpen = 'Y';
        if ($shopInfo['shopHolidayExists'] || $shopInfo['shopOptTimeExists']) {
            $ynOpen = 'N';
        }

        if ($ynOpen === 'Y') {
            $posError = SearchService::getPosError($shopInfo['no_shop']);
            if ($posError) {
                $ynOpen = 'N';
            }

            if ($shopInfo['ds_status'] === 'N') {
                $ynOpen = 'N';
            }

            if (isset($shopInfo['cd_pause_type'])) {
                $ynOpen = 'E';
            }
        }

        return $ynOpen;
    }

    /**
     * 매장 전체 영업시간
     * @param int $noShop
     *
     * @return Collection
     */
    public static function getInfoOptTimeAll(int $noShop): Collection
    {
        return ShopOptTime::where([
            'no_shop' => $noShop,
        ])->orderBy('nt_weekday')->get();
    }

    public static function shop(int $noShop): ?Collection
    {
        return Shop::where([
            'no_shop' => $noShop
        ])->get();
    }

    public static function getShop(int $noShop): ?Shop
    {
        return self::shop($noShop)->load([
            'shopDetail',
            'shopOptTime' => function ($q) {
                $q->where('nt_weekday', DB::raw('WEEKDAY(NOW())'));
            },
            'partner',
            'shopHolidayExists',
            'shopOptTimeExists',
            'partnerCategory' => function ($query) {
                return match (getAppType()) {
                    AppType::AVN => $query->where('no_partner_category', 'LIKE', '%9999'),
                    default => $query->where('no_partner_category', 'NOT LIKE', '%9999')
                };
            },
            'retailCategory.retailSubCategories',
            'shopOilPrice',
            'shopOil',
            'shopOilUnUseCard',
            'washInShop.shop.partner',
            'washInShop.shop.washProducts.washOptions',
            'washProducts.washOptions',
        ])->whenEmpty(function () {
            if (getAppType()->value == AppType::TMAP_AUTO->value) {
                throw new TMapException('SC1000', 400);
            }
            throw new OwinException(Code::message('9910'));
        })->map(function ($shop) {
            $shop->nm_shop = $shop->partner['nm_partner'] . ' ' . $shop->nm_shop;
            $shop->at_grade = ReviewService::getReviewTotal($shop->no_shop)?->at_grade;
            if ($shop->shopDetail) {
                $shop->shopDetail->ds_image_bg = Common::getImagePath($shop->shopDetail->ds_image_bg);
                $shop->shopDetail->ds_image2 = Common::getImagePath($shop->shopDetail->ds_image2);
                $shop->shopDetail->ds_image3 = Common::getImagePath($shop->shopDetail->ds_image3);
                $shop->shopDetail->ds_image4 = Common::getImagePath($shop->shopDetail->ds_image4);
                $shop->shopDetail->ds_image5 = Common::getImagePath($shop->shopDetail->ds_image5);
                $shop->shopDetail->ds_image6 = Common::getImagePath($shop->shopDetail->ds_image6);
                $shop->shopDetail->ds_image7 = Common::getImagePath($shop->shopDetail->ds_image7);
                $shop->shopDetail->ds_image8 = Common::getImagePath($shop->shopDetail->ds_image8);
                $shop->shopDetail->ds_image9 = Common::getImagePath($shop->shopDetail->ds_image9);
                $shop->shopDetail->ds_image10 = Common::getImagePath($shop->shopDetail->ds_image10);
                $shop->shopDetail->ds_image_pick1 = Common::getImagePath($shop->shopDetail->ds_image_pick1);
                $shop->shopDetail->ds_image_pick2 = Common::getImagePath($shop->shopDetail->ds_image_pick2);
                $shop->shopDetail->ds_image_pick3 = Common::getImagePath($shop->shopDetail->ds_image_pick3);
                $shop->shopDetail->ds_image_pick4 = Common::getImagePath($shop->shopDetail->ds_image_pick4);
                $shop->shopDetail->ds_image_pick5 = Common::getImagePath($shop->shopDetail->ds_image_pick5);
                $shop->shopDetail->ds_image_parking = Common::getImagePath($shop->shopDetail->ds_image_parking);

                $dsImage1 = $shop->shopDetail->ds_image1;
                if ($shop->no_partner === Code::conf('oil.gs_no_partner')) {
                    $dsImage1 = $dsImage1 && file_exists($dsImage1) ? $dsImage1 : '/data2/shop/1000/gs_default.jpg';
                } elseif ($shop->no_partner === Code::conf('oil.ex_no_partner')) {
                    $dsImage1 = $dsImage1 && file_exists($dsImage1) ? $dsImage1 : '/data2/shop/1426/ex_default.jpg';
                }
                $shop->shopDetail->ds_image1 = Common::getImagePath($dsImage1) ?: null;

                $shop->shopDetail->is_car_pickup = match (SearchBizKind::getBizKind($shop->partner->cd_biz_kind)) {
                    SearchBizKind::FNB => $shop->shopDetail->yn_car_pickup == 'Y',
                    default => true
                };
                $shop->shopDetail->is_shop_pickup = $shop->shopDetail->yn_shop_pickup == 'Y'; // 추후 기능 오픈
                $shop->shopDetail->is_booking_pickup = $shop->shopDetail->yn_booking_pickup == 'Y'; // 추후 기능 오픈
            }

            $shop->ds_menu_info = $shop->partner->ds_menu_origin ?? $shop->shopDetail?->ds_text10;
            $shop->nm_partner = $shop->partner->nm_partner;
            $shop->yn_open = ShopService::getYnShopOpen($shop);
            $shop->biz_kind = SearchBizKind::getBizKind($shop->partner->cd_biz_kind)->name;

            $shop->list_category = match (true) {
                $shop->partnerCategory->count() > 0 => self::getCategoryList($shop->partnerCategory),
                $shop->retailCategory->count() > 0 => self::getRetailCategoryList($shop->retailCategory),
                default => null
            };
            unset($shop->partnerCategory, $shop->retailCategory);

            if ($shop->washInShop) {
                $shop->wash_shop = $shop->washInShop->shop;
                $shop->wash_shop->nm_shop = $shop->washInShop->shop->partner->nm_partner . ' ' . $shop->washInShop->shop->nm_shop;
                $shop->wash_shop->partner->ds_bi = Common::getImagePath($shop->wash_shop->partner->ds_bi);
                $shop->wash_shop->partner->ds_pin = Common::getImagePath($shop->wash_shop->partner->ds_pin);
                unset($shop->washInShop);
            }

            $shop->partner->ds_bi = Common::getImagePath($shop->partner->ds_bi);
            $shop->partner->ds_pin = Common::getImagePath($shop->partner->ds_pin);
            return $shop;
        })->first();
    }

    private static function getCategoryList(Collection $list): Collection
    {
        return $list->map(function ($category) {
            return [
                'nm_category' => $category->nm_category,
                'yn_top' => null,
                'no_category' => $category->no_partner_category,
                'no_sub_category' => null
            ];
        })->values();
    }

    private static function getRetailCategoryList(Collection $list): Collection
    {
        return $list->map(function ($category) {
            return match ($category->retailSubCategories->count()) {
                0 => [
                    [
                        'nm_category' => $category->nm_category,
                        'yn_top' => $category->yn_top,
                        'no_category' => $category->no_category,
                        'no_sub_category' => null
                    ]
                ],
                default => $category->retailSubCategories->map(function ($subCategory) use ($category) {
                    return [
                        'nm_category' => sprintf('%s %s', $category->nm_category, $subCategory->nm_sub_category),
                        'yn_top' => $category->yn_top,
                        'no_category' => $category->no_category,
                        'no_sub_category' => $subCategory->no_sub_category,
                    ];
                })
            };
        })->flatten(1)->values();
    }

    public static function updateCtView(int $noShop): void
    {
        Shop::where(['no_shop' => $noShop])->increment('ct_view');
    }

    public static function updateShop(Shop $shop, array $parameter): void
    {
        $shop->updateOrFail($parameter);
    }

    public static function getShopOilPrices(int $noShop): Collection
    {
        return ShopOilPrice::select([
            DB::raw("*"),
            DB::raw("(SELECT nm_code FROM code_manage WHERE no_code = cd_gas_kind) AS nm_gas_kind"),
            DB::raw("ADDTIME(dt_trade, tm_trade) AS dt_trade")
        ])->where([
            'no_shop' => $noShop
        ])->get();
    }

    public static function getInfoCommission(int $noShop): ?Shop
    {
        return Shop::select([
            'cd_commission_type',
            'at_commission_amount',
            'at_commission_rate'
        ])->where([
            'no_shop' => $noShop
        ])->first();
    }

    public function getHoliday(int $noShop, ?int $addDays = 14): Collection
    {
        $dates = [];
        for ($i = 0; $i < $addDays; $i++) {
            $week = match (Common::getWeekByMonth(now()->addDays($i)->format('Y-m-d'))) {
                0 => 211300,
                1 => 211400,
                2 => 211500,
                3 => 211600,
                default => null
            };
            $day = now()->addDays($i)->dayOfWeek;
            $dates[$week][$day] = now()->addDays($i)->format('Y-m-d');
        }

        return ShopHoliday::where('no_shop', $noShop)
            ->whereNot('cd_holiday', '211100')->get()->map(function ($holiday) use ($dates, $addDays) {
                $weekday = $holiday->nt_weekday + 1 > 6 ? 0 : $holiday->nt_weekday + 1;
                $data = [];

                switch ($holiday->cd_holiday) {
                    case '211100':
                        break;
                    case '211200':
                        for ($i = 0; $i < $addDays; $i += 7) {
                            $data[] = [
                                'holiday' => now()->endOfWeek($weekday)->addDays($i)->format('Y-m-d'),
                                'break_start_time' => now()->startOfDay()->format('H:i:s'),
                                'break_end_time' => now()->endOfDay()->format('H:i:s')
                            ];
                        }
                        break;
                    case '211300':
                    case '211400':
                    case '211500':
                    case '211600':
                        $data[] = match (empty($dates[$holiday->cd_holiday][$weekday])) {
                            false => [
                                'holiday' => $dates[$holiday->cd_holiday][$weekday],
                                'break_start_time' => now()->startOfDay()->format('H:i:s'),
                                'break_end_time' => now()->endOfDay()->format('H:i:s')
                            ],
                            default => null
                        };
                        break;
                    case '211900':
                        if ($holiday->dt_imsi_end < now()) {
                            return;
                        }
                        $period = CarbonPeriod::create($holiday->dt_imsi_start->format('Y-m-d'), $holiday->dt_imsi_end->format('Y-m-d'));
                        foreach ($period as $date) {
                            if ($date->format('Y-m-d') < now()->format('Y-m-d')) continue;
                            $data[] = [
                                'holiday' => $date->format('Y-m-d'),
                                'break_start_time' => $holiday->dt_imsi_start->format('Y-m-d') == $date->format('Y-m-d') ? $holiday->dt_imsi_start->format('H:i:s') : '00:00:00',
                                'break_end_time' => $holiday->dt_imsi_end->format('Y-m-d') == $date->format('Y-m-d') ? $holiday->dt_imsi_end->format('H:i:s') : '23:59:00',
                            ];
                        }
                        break;
                }

                return $data;
            })->flatten(1)->filter()->sortBy('holiday')->values();
    }

    public function getOperate(int $noShop): Collection
    {
        $operate = Code::operate();

        return ShopOptTime::where('no_shop', $noShop)->get()->map(function ($opt) use ($operate) {
            return [
                'day_of_week' => $opt->nt_weekday + 1 > 6 ? 0 : $opt->nt_weekday + 1,
                'day_text' => $operate['day'][$opt->nt_weekday]['text'],
                'ds_open_time' => Carbon::createFromFormat('Hi', $opt->ds_open_time)->format('H:i:s'),
                'ds_close_time' => Carbon::createFromFormat('Hi', $opt->ds_close_time)->format('H:i:s'),
                'ds_open_order_time' => $opt->ds_open_order_time ? Carbon::createFromFormat(
                    'Hi',
                    $opt->ds_open_order_time
                )->format('H:i:s') : null,
                'ds_close_order_time' => $opt->ds_close_order_time ? Carbon::createFromFormat(
                    'Hi',
                    $opt->ds_close_order_time
                )->format('H:i:s') : null,
                'break1' => [
                    'type' => $opt->cd_break_time,
                    'text' => $operate['break'][$opt->cd_break_time] ?? null,
                    'start_time' => $opt->ds_break_start_time,
                    'end_time' => $opt->ds_break_end_time
                ],
                'break2' => [
                    'type' => $opt->cd_break_time2,
                    'text' => $operate['break'][$opt->cd_break_time2] ?? null,
                    'start_time' => $opt->ds_break_start_time2,
                    'end_time' => $opt->ds_break_end_time2
                ]
            ];
        });
    }

    /**
     * 주유 주문 안되는 카드 return
     * @param int $noShop
     * @return Collection
     */
    public static function getShopUnUseCards(int $noShop): Collection
    {
        return ShopOilUnuseCard::where([
            'no_shop' => $noShop,
            'yn_unuse_status' => 'Y'
        ])->get();
    }
}
