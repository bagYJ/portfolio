<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnumYN;
use App\Enums\SearchBizKind;
use App\Models\CouponEventCondition;
use App\Models\GsCpnEvent;
use App\Models\MemberCard;
use App\Models\MemberCoupon;
use App\Models\MemberCouponRequest;
use App\Models\MemberOwinCouponRequest;
use App\Models\MemberParkingCoupon;
use App\Models\MemberPartnerCoupon;
use App\Models\MemberRetailCoupon;
use App\Models\MemberRetailCouponRequest;
use App\Models\MemberWashCoupon;
use App\Models\ParkingSite;
use App\Models\ParkingSiteTicket;
use App\Models\Partner;
use App\Models\RetailCouponEvent;
use App\Models\Shop;
use App\Services\Gs\GsService;
use App\Utils\Code;
use App\Utils\Common;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use function collect;
use function now;

class CouponService extends Service
{
    /**
     * @param int $noUser
     * @param string|null $useCouponYn
     * @param int|null $cdThirdParty
     *
     * @return Collection
     */
    public function myRetailCoupon(
        int $noUser,
        ?string $useCouponYn,
        ?int $cdThirdParty = null
    ): Collection {
        return MemberRetailCoupon::join(
            'retail_coupon_event AS rce',
            'member_retail_coupon.no_event',
            '=',
            'rce.no'
        )
            ->with('memberRetailCouponUsepartner.partner')
            ->where('no_user', $noUser)
            ->where('dt_use_end', '>', now())
            ->when($useCouponYn, function ($query) use ($useCouponYn) {
                $query->where('use_coupon_yn', $useCouponYn);
            })->when($cdThirdParty, function ($query) use ($cdThirdParty) {
                $query->where('cd_third_party', $cdThirdParty);
            })->get()->map(function ($coupon) {
                $coupon->no_event = $coupon->no_coupon;
                $coupon->coupon_type = SearchBizKind::RETAIL->name;
                $coupon->at_discount = number_format($coupon->at_disct_money)
                    . Code::message('126100');
                $coupon->dt_expire = $coupon->dt_use_end;
                $coupon->at_price_limit = $coupon->at_min_price;
                $coupon->available_shop = $coupon->memberRetailCouponUsepartner
                    ->map(function ($usePartner) {
                        return $usePartner->partner->nm_partner;
                    })->join(', ');

                return $coupon;
            });
    }

    public function myFnbCoupon(
        int $noUser,
        ?string $useCouponYn,
        ?int $cdThirdParty = null,
        ?int $noEvent = null
    ): Collection {
        return MemberCoupon::join(
            'coupon_event',
            'member_coupon.no_event',
            '=',
            'coupon_event.no_event'
        )->with([
            'couponEventProduct',
            'couponEventCondition.partner',
            'couponEventCondition.shop',
            'couponEventCondition.card',
            'couponEventCondition.partnerCategory',
            'couponEventCondition.product'
        ])->where('no_user', $noUser)
            ->where(
                function ($query) use ($useCouponYn, $cdThirdParty, $noEvent) {
                    match ($useCouponYn) {
                        EnumYN::N->name => $query->where(
                            'cd_cpe_status',
                            '121200'
                        )
                            ->where('cd_mcp_status', '!=', '122100'),
                        EnumYN::Y->name => $query->where(
                            'cd_cpe_status',
                            '121100'
                        )
                            ->where('cd_mcp_status', '122100')
                            ->where(function ($query) {
                                $query->where('dt_start', '<=', now())
                                    ->orWhereNull('dt_expire');
                            })->where(function ($query) {
                                $query->where('dt_expire', '>=', now())
                                    ->orWhereNull('dt_expire');
                            }),
                        default => null
                    };
                    if (empty($cdThirdParty) === false) {
                        $query->where(
                            'coupon_event.cd_third_party',
                            $cdThirdParty
                        );
                    }
                    if (empty($noEvent) === false) {
                        $query->where('coupon_event.no_event', $noEvent);
                    }
                }
            )->select(
                [
                    'coupon_event.*',
                    'member_coupon.cd_mcp_status',
                    'member_coupon.no_order',
                    'member_coupon.dt_reg'
                ]
            )->get()->map(function ($coupon) {
                $coupon->coupon_type = SearchBizKind::FNB->name;
                $coupon->discount_type = CodeService::getCode(
                    $coupon->couponEvent->cd_disc_type
                )->nm_code;
                $coupon->at_discount = match ($coupon->couponEvent->cd_disc_type) {
                    '126300' => $coupon->couponEventProduct->nm_product . Code::message($coupon->couponEvent->cd_disc_type),
                    default => number_format($coupon->couponEvent->at_discount) . Code::message($coupon->couponEvent->cd_disc_type)
                };
                $coupon->available_partner = $coupon->couponEventCondition->where(
                    'cd_cpn_condi_type',
                    '125100'
                )->map(function ($cond) {
                    return [
                        'nm_partner' => $cond->partner->nm_partner
                    ];
                })->pluck('nm_partner')->join(', ');

                $coupon->available_shop = $coupon->couponEventCondition->where(
                    'cd_cpn_condi_type',
                    '125200'
                )->map(function ($cond) {
                    return [
                        'nm_shop' => $cond->shop->nm_shop
                    ];
                })->pluck('nm_shop')->join(', ');

                $coupon->available_card = $coupon->couponEventCondition->where(
                    'cd_cpn_condi_type',
                    '125300'
                )->first()?->card->nm_code;
                $coupon->available_weekday = $coupon->couponEventCondition->where('cd_cpn_condi_type', '125400')
                    ->map(function ($cond) {
                        $week = $cond->ds_target - 2 >= 0 ? $cond->ds_target - 2 : $cond->ds_target + 5;
                        return ['nm_shop' => Code::operate(sprintf('day.%s.text', $week))];
                    })->pluck('nm_shop')->join(', ');
                $coupon->available_category = $coupon->couponEventCondition->where('cd_cpn_condi_type', '125500')
                    ->map(function ($cond) {
                        return [
                            'nm_category' => $cond->partnerCategory->nm_category
                        ];
                    })->pluck('nm_category')->join(', ');
                $coupon->available_product = $coupon->couponEventCondition->where('cd_cpn_condi_type', '125600')
                    ->map(function ($cond) {
                        return [
                            'nm_product' => $cond->product->nm_product
                        ];
                })->pluck('nm_product')->join(', ');
                $coupon->at_price_limit = $coupon->couponEventCondition->where('cd_cpn_condi_type', '125700')->first()?->ds_target;

                return $coupon;
            });
    }

    public function myOilCoupons(
        int $noUser,
        ?string $useCouponYn,
        ?int $cdThirdParty = null
    ): Collection {
        return MemberPartnerCoupon::with(['gsCpnEvent'])->where([
            'cd_mcp_status' => '122100',
            'cd_cpe_status' => '121100',
        ])->where('member_partner_coupon.no_user', $noUser)
            ->where('member_partner_coupon.dt_use_end', '>', now())->when(
                $useCouponYn,
                function ($query) use ($useCouponYn) {
                    $query->where(
                        'use_coupon_yn',
                        $useCouponYn
                    );
                }
            )->get()->filter(function ($query) use ($cdThirdParty) {
                return ($cdThirdParty && $query['gsCpnEvent']
                        && $cdThirdParty
                        == $query['gsCpnEvent']['cd_third_party'])
                    || ! $query['gsCpnEvent'];
            })->map(function ($coupon) {
                $coupon->no_event = $coupon->ds_cpn_no;
                $coupon->coupon_type = SearchBizKind::OIL->name;
                $coupon->nm_event = $coupon->ds_cpn_nm;
                $coupon->at_discount = $coupon->at_disct_money;
                $coupon->dt_expire = $coupon->dt_use_end;

                return $coupon;
            });
    }

    public function myCoupon(
        int $noUser,
        ?string $useCouponYn,
        ?int $cdThirdParty = null
    ): Collection {
        return $this->myRetailCoupon($noUser, $useCouponYn, $cdThirdParty)
            ->collect()
            ->merge($this->myFnbCoupon($noUser, $useCouponYn, $cdThirdParty))
            ->merge($this->myOilCoupons($noUser, $useCouponYn, $cdThirdParty))
            ->merge($this->myWashCoupon($noUser, $useCouponYn))
            ->merge($this->myParkingCoupon($noUser, $useCouponYn));
    }

    public function myParkingCoupon(
        int $noUser,
        ?string $useCouponYn,
    ): Collection {
        return MemberParkingCoupon::join(
            'parking_coupon_event',
            'parking_coupon_event.no',
            '=',
            'member_parking_coupon.no_event'
        )->where('no_user', $noUser)
            ->with(['couponEvent'])
            ->where(function ($query) use ($useCouponYn) {
                match ($useCouponYn) {
                    EnumYN::N->name => $query->where('cd_cpe_status', '121200')->where('cd_mcp_status', '!=', '122100'),
                    EnumYN::Y->name => $query->where('cd_cpe_status', '121100')->where('cd_mcp_status', '122100')
                        ->where(function ($query) {
                            $query->where('dt_start', '<=', now())->orWhereNull(
                                'dt_end'
                            );
                        })->where(function ($query) {
                            $query->where('dt_end', '>=', now())->orWhereNull(
                                'dt_end'
                            );
                        })->where(
                            function ($query) {
                            $query->whereNotNull('member_parking_coupon.at_expire_day')
                                ->where('member_parking_coupon.dt_reg', '>',
                                    DB::raw("DATE_ADD(NOW(), INTERVAL - member_parking_coupon.at_expire_day DAY)")
                            );
                        }
                        ),
                    default => null
                };
            })->select([
                'parking_coupon_event.*',
                'member_parking_coupon.cd_mcp_status',
                'member_parking_coupon.no_order',
                'member_parking_coupon.dt_reg',
            ])->get()->map(function ($coupon) {
                $coupon->no_event = $coupon->no;
                $coupon->coupon_type = SearchBizKind::PARKING->name;
                $coupon->discount_type = CodeService::getCode($coupon->cd_disct_type)->nm_code;
                $coupon->at_discount = match ($coupon->cd_disct_type) {
                    '126200' => $coupon->at_disct_rate . Code::message($coupon->cd_disct_type),
                    default => number_format($coupon->at_disct_money) . Code::message($coupon->cd_disct_type)
                };
                if ($coupon->dt_end) {
                    $coupon->dt_expire = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->dt_end . ' 00:00:00');
                } elseif ($coupon->at_expire_day) {
                    $coupon->dt_expire = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->dt_reg)->addDays($coupon->at_expire_day);
                }
                if ($coupon->no_sites && $coupon->no_sites['no_parking_site']) {
                    $coupon->available_shop = ParkingSite::whereIn('no_parking_site', $coupon->no_sites['no_parking_site'])
                        ->pluck('nm_shop')->join(',');
                }

                return $coupon;
            });
    }

    public function myWashCoupon(
        int $noUser,
        ?string $useCouponYn,
    ): Collection {
        $where = [
            ['no_user', '=', $noUser],
            ['cd_mcp_status', '=', '122100'],
            ['dt_use_start', '<=', DB::raw("CURRENT_DATE()")],
            ['dt_use_end', '>=', DB::raw("CURRENT_DATE()")],
        ];

        if ($useCouponYn) {
            $where[] = ['use_coupon_yn', '=', $useCouponYn];
        }

        return MemberWashCoupon::where($where)
            ->with([
                'washConditions.partner',
                'washConditions.shop.partner',
            ])->orderBy("dt_use_end", 'ASC')->get()->map(function ($coupon) {
                $coupon->coupon_type = SearchBizKind::WASH->name;
                $coupon->discount_type = CodeService::getCode('126100')->nm_code;
                $coupon->at_discount = number_format($coupon->at_disct_money) . '원';
                $coupon->available_partner = $coupon->washConditions->where('cd_cpn_condi_type', '125100')
                    ->map(function ($cond) {
                        return [
                            'nm_partner' => $cond->partner->nm_partner
                        ];
                })->pluck('nm_partner')->join(', ');

                $coupon->available_shop = $coupon->washConditions->where('cd_cpn_condi_type', '125200')
                    ->map(function ($cond) {
                        return [
                            'nm_shop' => $cond->shop->nm_shop
                        ];
                    })->pluck('nm_shop')->join(', ');

                $coupon->dt_expire = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->dt_use_end);
                return $coupon;
            });
    }

    /**
     * @param Collection $coupon
     *
     * @return Collection
     */
    public function getUseCoupons(Collection $coupon): Collection
    {
        $useCouponPartner = match ($coupon->where('cd_cpn_condi_type', '125100')
                ->count() > 0) {
            true => Partner::whereIn(
                'no_partner',
                $coupon->where('cd_cpn_condi_type', '125100')->pluck('ds_target', 'ds_target')
            )->get(['no_partner', 'cd_biz_kind', 'nm_partner', 'ds_pin'])->map(
                function ($partner) {
                    return collect([
                        'no' => $partner->no_partner,
                        'nm_shop' => $partner->nm_partner,
                        'cd_biz_kind' => $partner->cd_biz_kind,
                        'nm_partner' => $partner->nm_partner,
                        'ds_pin' => $partner->ds_pin
                    ]);
                }
            )->keyBy('no'),
            false => collect([])
        };

        $useCouponShop = Shop::with('partner')->whereIn(
            'no_shop',
            $coupon->where('cd_cpn_condi_type', '125200')->pluck('ds_target', 'ds_target')
        )->get(['no_shop', 'nm_shop', 'no_partner'])->map(function ($shop) {
            return collect([
                'no' => $shop->no_shop,
                'nm_shop' => $shop->nm_shop . ' ' . $shop->partner->nm_partner,
                'cd_biz_kind' => $shop->partner->cd_biz_kind,
                'nm_partner' => $shop->partner->nm_partner,
                'ds_pin' => Common::getImagePath($shop->partner->ds_pin),
            ]);
        })->keyBy('no');

        return $useCouponPartner->merge($useCouponShop);
    }

    public function memberCoupon(array $parameter): Collection
    {
        return MemberCoupon::where($parameter)->get();
    }

    public static function memberPartnerCoupon(array $parameter = [], array $couponNos = []): Collection
    {
        return MemberPartnerCoupon::when(empty($parameter) === false, function ($query) use ($parameter) {
            $query->where($parameter);
        })->when(empty($couponNos) === false, function ($query) use ($couponNos) {
            $query->whereIn('no', $couponNos);
        })->orderBy('dt_use_end')->orderByDesc('at_disct_money')->orderBy('dt_reg')->get();
    }

    public static function removeMemberPartnerCoupon(MemberPartnerCoupon $coupon): void
    {
        $coupon->delete();
    }

    public function memberWashCoupon(
        ?array $parameter = [],
        ?array $couponNos = []
    ): Collection {
        $memberWashCoupon = new MemberWashCoupon();

        if ($parameter) {
            $memberWashCoupon = $memberWashCoupon->where($parameter);
        }

        if ($couponNos) {
            $memberWashCoupon = $memberWashCoupon->whereIn('no', $couponNos);
        }

        return $memberWashCoupon->orderBy('dt_use_end')->orderByDesc('at_disct_money')->orderBy('dt_reg')->get();
    }

    public static function todayCouponRequest(array $parameter): Collection
    {
        return MemberCouponRequest::where($parameter)
            ->where('dt_reg', '>', now()->startOfDay())->get();
    }

    public static function memberPartnerCouponRegist(array $parameter): void
    {
        (new MemberPartnerCoupon($parameter))->saveOrFail();
    }

    public static function memberCouponRequestRegist(array $parameter): void
    {
        (new MemberCouponRequest($parameter))->saveOrFail();
    }

    public function memberPartnerCouponLastRegist(?array $parameter): Collection
    {
        return $this->memberPartnerCoupon($parameter)->sortByDesc('dt_reg')
            ->first();
    }

    public function todayMemberOwinCouponRequest(array $parameter): Collection
    {
        return MemberOwinCouponRequest::where($parameter)
            ->where('dt_reg', '>', now()->startOfDay())->get();
    }

    public function gsCouponEvent(array $parameter): Collection
    {
        return GsCpnEvent::where($parameter)->get();
    }

    public static function retailCouponEvent(array $parameter): Collection
    {
        return RetailCouponEvent::with('retailCouponEventUsepartner.partner')->where($parameter)->get();
    }

    public function memberRetailCoupon(array $parameter): Collection
    {
        return MemberRetailCoupon::with('retailCouponEventUsepartner')->where($parameter)->get();
    }

    public function memberRetailCouponRegist(array $parameter): void
    {
        (new MemberRetailCoupon($parameter))->saveOrFail();
    }

    public function memberRetailCouponRequestRegist(array $parameter): void
    {
        (new MemberRetailCouponRequest($parameter))->saveOrFail();
    }

    public function refund($noUser, $noOrder)
    {
        MemberCoupon::where([
            'no_user'  => $noUser,
            'no_order' => $noOrder
        ])->update([
            'cd_mcp_status' => '122100',
            'no_order' => null,
            'dt_upt' => DB::raw("NOW()"),
        ]);
    }

    public function getRetailUsableCoupon(
        int $noUser,
        int $noPartner,
        int $totalPrice
    ): Collection {
        return MemberRetailCoupon::with([
            'retailCouponEvent.retailCouponEventUsepartner' => function ($query) use ($noPartner) {
                $query->where('no_partner', $noPartner);
            }
        ])->leftJoin(
            'retail_coupon_event AS rce',
            'member_retail_coupon.no_event',
            '=',
            'rce.no'
        )->where([
            'member_retail_coupon.no_user' => $noUser,
            'member_retail_coupon.cd_mcp_status' => '122100',
            'member_retail_coupon.use_coupon_yn' => 'Y',
            'rce.cd_third_party' => getAppType()->value
        ])->whereBetween(
            DB::raw('curdate()'),
            [
                DB::raw('member_retail_coupon.dt_use_start'),
                DB::raw('member_retail_coupon.dt_use_end')
            ])
            ->where('member_retail_coupon.at_min_price', '<=', $totalPrice)
            ->get()->map(function ($coupon) use ($totalPrice) {
                return [
                    'no' => $coupon->no_coupon,
                    'nm_event' => $coupon->nm_event,
                    'coupon_type' => 'DISCOUNT',
                    'at_discount' => min($coupon->at_disct_money, $totalPrice),
                    'required_card' => null,
                    'gift' => null
                ];
            });
    }

    public function getOilUsableCoupon(
        int $noUser,
        int $noShop,
        int $totalPrice,
        float|int $liter,
        MemberCard $card
    ): Collection {
        return MemberPartnerCoupon::where([
            'cd_cpe_status' => '121100',
            'cd_mcp_status' => '122100',
            'no_user' => $noUser,
        ])->whereBetween(
            DB::raw('now()'),
            [DB::raw('dt_use_start'), DB::raw('dt_use_end')]
        )->get()->map(
            function ($coupon) use ($totalPrice, $card, $liter) {
                if ($coupon->use_disc_type == '1') {
                    //금액 조건 충족 X
                    if ($coupon->at_limit_money > 0 && $coupon->at_limit_money >= $totalPrice) {
                        return;
                    }

                    //카드 조건 충족 X
                    if ($coupon->cd_payment_card != $card->cd_card_corp) {
                        return;
                    }

                    //리터 조건 충족 X
                    if ($coupon->at_condi_liter && $coupon->at_condi_liter >= $liter) {
                        return;
                    }
                }

                $discountMaxCoupon = min($coupon->at_disct_money, $totalPrice);
                return [
                    'no' => $coupon->no,
                    'no_event' => $coupon->no_event,
                    'nm_event' => $coupon->ds_cpn_nm,
                    'coupon_type' => 'DISCOUNT',
                    'at_discount' => $discountMaxCoupon,
                    'required_card' => $coupon->cd_payment_card,
                    'ds_cpn_no_internal' => $coupon->ds_cpn_no_internal,
                    'ds_cpn_no' => $coupon->ds_cpn_no,
                    'yn_real_pubs' => $coupon->yn_real_pubs,
                ];
            }
        )->filter()->sortByDesc('at_discount')->sortBy('dt_expire')->values();
    }

    public function getFnbUsableCoupon(
        int $noUser,
        int $noShop,
        int $totalPrice,
        Collection $products
    ): Collection {
        return MemberCoupon::with(['couponEventCondition', 'couponEventProduct'])
            ->leftJoin(
            'coupon_event AS ce',
            'member_coupon.no_event',
            '=',
            'ce.no_event'
            )
            ->where([
                'member_coupon.no_user'       => $noUser,
                'member_coupon.cd_mcp_status' => '122100', // 미사용 쿠폰
                'ce.cd_cpe_status'            => '121100',
                'ce.cd_third_party'           => getAppType()->value
            ])->whereBetween(
                DB::raw('now()'),
                [DB::raw('ce.dt_start'), DB::raw('ce.dt_expire')]
            )->get()
            ->map(function ($coupon) use ($noShop, $totalPrice, $products) {
//                브랜드쿠폰
                if ($coupon->yn_condi_status_partner == EnumYN::Y->name
                    && $coupon->couponEventCondition->where('cd_cpn_condi_type', '125100')
                        ->where('ds_target', substr((string)$noShop, 0, 4))->count() <= 0
                ) {
                    return;
                }
//                매장
                if ($coupon->yn_condi_status_shop == EnumYN::Y->name
                    && $coupon->couponEventCondition->where('cd_cpn_condi_type', '125200')
                        ->where('ds_target', $noShop)->count() <= 0
                ) {
                    return;
                }
//                요일
                if ($coupon->yn_condi_status_weekday == EnumYN::Y->name
                    && $coupon->couponEventCondition->where('cd_cpn_condi_type', '125400')
                        ->where('ds_target', now()->dayOfWeek + 1)->count() <= 0
                ) {
                    return;
                }
//                카테고리
                if ($coupon->yn_condi_status_category == EnumYN::Y->name
                    && $coupon->couponEventCondition->where('cd_cpn_condi_type', '125500')
                        ->whereIn('ds_target', data_get($products, '*.category'))->count() <= 0
                ) {
                    return;
                }
//                상품
                if ($coupon->yn_condi_status_menu == EnumYN::Y->name
                    && ($coupon->couponEventCondition->where('cd_cpn_condi_type', '125600')->whereIn('ds_target', data_get($products, '*.no_product'))->count() <= 0
                    || ProductService::getProduct([
                        'no_product' => $coupon->at_discount,
                        'ds_status' => 'Y'
                    ])->count() <= 0)
                ) {
                    return;
                }
//                구매금액
                if ($coupon->yn_condi_status_money == EnumYN::Y->name
                    && $coupon->couponEventCondition->where('cd_cpn_condi_type', '125700')
                        ->where('ds_target', '<=', $totalPrice)->count() <= 0
                ) {
                    return;
                }

                $couponDiscount = match ($coupon->cd_disc_type) {
                    '126100' => $coupon->at_discount,
                    '126200' => floor($totalPrice * ($coupon->at_discount * 0.001)) * 10, // 1원단위 절삭
                    '126300' => match (in_array($coupon->at_discount, data_get($products, '*.no_product'))) {
                        true => collect($products)->firstWhere(
                            'no_product',
                            $coupon->at_discount
                        )['at_price'],
                        default => 0
                    },
                    default => null
                };

                $discountMaxCoupon = min($couponDiscount, $totalPrice);

                return [
                    'no' => $coupon->no_event,
                    'nm_event' => $coupon->nm_event,
                    'coupon_type' => match ($coupon->cd_disc_type) {
                        '126100', '126200' => 'DISCOUNT',
                        '126300' => 'GIFT',
                        default => null
                    },
                    'at_discount' => $coupon->at_max_disc > 0 ? min($discountMaxCoupon, $coupon->at_max_disc) : $discountMaxCoupon,
                    'required_card' => $coupon->couponEventCondition->where('cd_cpn_condi_type', '125300')->first()?->ds_target,
                    'gift' => $coupon->couponEventProduct?->no_product
                ];
            })->filter()->sortByDesc('at_discount')->sortBy('dt_expire')
            ->values();
    }

    public function getParkingUsableCoupon(
        int $noUser,
        int $noSite,
        int $totalPrice,
        ParkingSiteTicket $ticket
    ): Collection {
        return MemberParkingCoupon::with(['couponEvent'])
            ->join(
                'parking_coupon_event',
                'parking_coupon_event.no',
                '=',
                'member_parking_coupon.no_event'
            )
            ->where([
                'member_parking_coupon.no_user' => $noUser,
                'member_parking_coupon.cd_mcp_status' => '122100', // 미사용 쿠폰
                'parking_coupon_event.cd_cpe_status' => '121100',
            ])->get()->map(
                function ($coupon) use ($noSite, $totalPrice) {
                    if ($coupon->no_sites
                        && $coupon->no_sites['no_parking_site']
                        && ! in_array($noSite, $coupon->no_sites['no_parking_site'])) {
                        return;
                    }

                    if ($coupon->dt_start && $coupon->dt_start < Carbon::now()->format('Y-m-d')) {
                        return;
                    }

                    $couponDiscount = match ($coupon->cd_disct_type) {
                        '126100' => $coupon->at_disct_money,
                        '126200' => floor($totalPrice * ($coupon->at_disct_rate / 100)),
                        default => null
                    };

                    $discountMaxCoupon = min($couponDiscount, $totalPrice);

                    $dtExpire = null;
                    if ($coupon->dt_end) {
                        $dtExpire = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->dt_end . ' 00:00:00');
                    } elseif ($coupon->at_expire_day) {
                        $dtExpire = Carbon::createFromFormat('Y-m-d H:i:s', $coupon->dt_reg)->addDays($coupon->at_expire_day);
                    }

                    if ($dtExpire <= Carbon::now()->format('Y-m-d H:i:s')) {
                        return;
                    }

                    return [
                        'no' => $coupon->no_coupon,
                        'nm_event' => $coupon->nm_event,
                        'coupon_type' => 'DISCOUNT',
                        'at_discount' => $discountMaxCoupon,
                        'dt_expire' => $dtExpire,
                    ];
                }
            )->filter()->sortByDesc('at_discount')->sortBy('dt_expire')
            ->values();
    }

    public function getWashUsableCoupon(
        int $noUser,
        int $noShop,
        int $totalPrice,
    ): ?Collection {
        return MemberWashCoupon::select([
            'member_wash_coupon.*',
            'ce.*',
            'member_wash_coupon.no AS no',
            'member_wash_coupon.at_disct_money AS at_disct_money'
        ])->with(['washConditions'])->leftJoin(
            'wash_coupon_event AS ce',
            'member_wash_coupon.no_event',
            '=',
            'ce.no'
        )
            ->where([
                'no_user' => $noUser,
                'cd_mcp_status' => '122100', // 미사용 쿠폰
            ])->whereBetween(
                DB::raw('now()'),
                [DB::raw('dt_use_start'), DB::raw('dt_use_end')]
            )->get()
            ->map(function ($coupon) use ($noShop, $totalPrice) {
                if ($coupon->washConditions->where(
                    'cd_cpn_condi_type',
                    '125100'
                )->count()
                    && $coupon->washConditions->where('cd_cpn_condi_type', '125100')
                        ->where('ds_target', substr((string)$noShop, 0, 4))->count() <= 0
                ) {
                    return;
                }
                if ($coupon->washConditions->where('cd_cpn_condi_type', '125200')->count()
                    && $coupon->washConditions->where('cd_cpn_condi_type', '125200')
                        ->where('ds_target', $noShop)->count() <= 0
                ) {
                    return;
                }

                return [
                    'no' => $coupon->no,
                    'nm_event' => $coupon->nm_event,
                    'coupon_type' => 'DISCOUNT', //세차는 무조건 금액 할인
                    'at_discount' => min($coupon->at_disct_money, $totalPrice),
                ];
            })->sortByDesc('at_discount_money')->sortBy('dt_use_end')->values();
    }

    public function usedMemberCoupon(
        string $noOrder,
        int $noEvent,
        int $noUser,
        string $nmShop
    ): void {
        MemberCoupon::where([
            'no_user' => $noUser,
            'no_event' => $noEvent
        ])->update([
            'cd_mcp_status' => '122200',
            'no_order' => $noOrder,
            'ds_etc' => $nmShop
        ]);
    }

    public function usedMemberWashCoupon(
        string $noOrder,
        int $noEvent,
        int $noUser,
        string $nmShop
    ): void {
        MemberWashCoupon::where([
            'no_user' => $noUser,
            'no_event' => $noEvent,
        ])->update([
            'cd_mcp_status' => '122200',
            'no_order_wash' => $noOrder,
            'dt_use' => now(),
        ]);
    }

    public function usedMemberParkingCoupon(
        string $noOrder,
        int $noCoupon,
        int $noUser,
        int $totalPrice = 0,
    ): void {
        MemberParkingCoupon::where([
            'no_user' => $noUser,
            'no_coupon' => $noCoupon
        ])->update([
            'use_coupon_yn' => 'N',
            'cd_mcp_status' => '122200',
            'no_order' => $noOrder,
            'at_price' => $totalPrice,
            'dt_use' => now(),
        ]);
    }


    public function usedMemberRetailCoupon(
        string $noOrder,
        string $noCoupon,
        int $noUser
    ): void {
        MemberRetailCoupon::where([
            'no_user' => $noUser,
            'no_coupon' => $noCoupon
        ])->update([
            'use_coupon_yn' => 'N',
            'cd_mcp_status' => '122200',
            'dt_use' => now(),
            'no_order_retail' => $noOrder
        ]);
    }

    /**
     * 세차 쿠폰 환불
     *
     * @param string $noOrder
     * @param int $noUser
     *
     * @return void
     */
    public function refundMemberWashCoupon(string $noOrder, int $noUser): void
    {
        MemberWashCoupon::where([
            'no_user' => $noUser,
            'no_order_wash' => $noOrder
        ])->update([
            'cd_mcp_status' => '122100',
            'dt_use' => null,
            'no_order_wash' => null,
            'dt_upt' => Carbon::now(),
        ]);
    }

    /**
     * fnb 쿠폰 환불
     *
     * @param string $noOrder
     * @param int    $noUser
     *
     * @return void
     */
    public function refundMemberCoupon(string $noOrder, int $noUser): void
    {
        MemberCoupon::where([
            'no_user' => $noUser,
            'no_order' => $noOrder
        ])->update([
            'cd_mcp_status' => '122100',
            'dt_upt' => '',
            'no_order' => '',
            'ds_etc' => ''
        ]);
    }

    /**
     * 주차 쿠폰 환불
     *
     * @param string $noOrder
     * @param int $noUser
     *
     * @return void
     */
    public function refundMemberParkingCoupon(
        string $noOrder,
        int $noUser
    ): void {
        MemberParkingCoupon::where([
            'no_user' => $noUser,
            'no_order' => $noOrder,
        ])->update([
            'cd_mcp_status' => '122100',
            'at_price' => null,
            'no_order' => null,
            'dt_use' => null,
        ]);
    }

    /**
     * 주유 쿠폰 환불
     *
     * @param $noUser
     * @param $noOrder
     *
     * @return void
     */
    public static function refundMemberPartnerCoupon($noOrder, $noUser)
    {
        MemberPartnerCoupon::where([
            'no_order' => $noOrder,
            'no_user' => $noUser
        ])->update([
            'use_coupon_yn' => 'Y',
            'cd_cpe_status' => '121100',
            'cd_mcp_status' => '122100',
            'cd_payment_status' => null,
            'no_order' => null,
        ]);
    }

    /**
     * 리테일 쿠폰 환불
     *
     * @param string $noOrder
     * @param int $noUser
     *
     * @return void
     */
    public function refundMemberRetailCoupon(string $noOrder, int $noUser): void
    {
        MemberRetailCoupon::where([
            'no_user' => $noUser,
            'no_order_retail' => $noOrder
        ])->update([
            'use_coupon_yn'  => 'Y',
            'cd_mcp_status' => '122100',
            'dt_use' => null,
            'no_order_retail' => null
        ]);
    }

    /**
     * 결제시 사용가능한 사용자 쿠폰리스트 반환 -  init
     *
     * @param int $noUser
     *
     * @return array
     */
    public static function myOilCoupon(int $noUser): array
    {
        $result  = [];
        $coupons = MemberPartnerCoupon::select([
            DB::raw("ds_cpn_no AS no_event"),
            'no_user',
            'no_partner',
            'use_coupon_yn',
            DB::raw("ds_cpn_nm AS nm_event"),
            'use_disc_type',
            'at_disct_money',
            'at_limit_money',
            'cd_payment_card',
            'cd_mcp_status',
            'cd_cpe_status',
            'dt_reg',
            DB::raw("(SELECT ds_pin FROM partner WHERE no_partner = member_partner_coupon.no_partner) AS ds_pin"),
            DB::raw("'9002' AS kind"),
            DB::raw("at_disct_money AS at_discount"),
            DB::raw("'126100' AS cd_disc_type"),
            DB::raw("dt_use_end AS dt_expire"),
            'dt_use_start',
            DB::raw("ds_result_code AS ds_result_code"),
        ])->with([
            'couponEventCondition' => function ($q) {
                $q->select([
                    'partner.nm_partner',
                    'partner.ds_pin'
                ]);
                $q->leftJoin(
                    'partner',
                    DB::raw("SUBSTRING(coupon_event_condition.ds_target,1,4)"),
                    '=',
                    'partner.no_partner'
                );
                $q->where([
                    ['partner.nm_partner', '<>', null]
                ]);
            },
            'partner',
        ])->where([
            'no_user' => $noUser,
            'cd_cpe_status' => '121100',
            'cd_mcp_status' => '122100',
        ])->orderByDesc('at_disct_money')->orderBy('dt_reg')->get();

        // 사용할 수 있는 쿠폰이 있으면
        if (count($coupons)) {
            $couponEventNos = $coupons->pluck('no_event')->all();
            // 보유한 쿠폰의 이벤트 조건  전체조회
            $condition = self::getCouponCondition($couponEventNos);
            $today = date('Y-m-d H:i:s');

            foreach ($coupons as $coupon) {
                $startDate = date('Y-m-d H:i:s', strtotime($coupon['dt_use_start'] . ' -1 seconds'));
                $endDate   = null;
                if ($coupon['dt_expire']) {
                    $endDate = date('Y-m-d H:i:s', strtotime($coupon['dt_expire'] . ' +1 seconds'));
                }

                if ($today > $startDate && ($endDate && $today < $endDate)) {
                    if (isset($condition[$coupon['no_event']])) {
                        $validCouponCondition = self::validCouponCondition($coupon, $condition[$coupon['no_event']]);
                    }
                    $data = self::getCouponViewTxt($coupon, Code::conf('biz_kind.oil'));

                    $dtExpireTxt = $data['dt_expire'];
                    if ($dtExpireTxt != "만료일없음") {
                        // 지갑- 쿠폰관리
                        $data['dt_expire'] = str_replace(substr($dtExpireTxt, 0, 5), "", $dtExpireTxt); // 7/31까지
                    }

                    $data['ds_result_code'] = $coupon['ds_result_code']; //제휴사응답코드
                    $data['no_event'] = $coupon['no_event'];    // 이벤트번호(쿠폰번호)
                    $data['nm_event'] = $coupon['nm_event'];    // 이벤트명(쿠폰명)
                    $data['yn_dupl_use'] = ($coupon['yn_dupl_use'] == 'Y') ? 'Y' : 'N'; // 중복사용가능여부
                    $data['yn_use'] = $coupon['use_coupon_yn']; // 사용가능여부
                    $data['use_no_shop'] = $validCouponCondition['use_no_shop'] ?? null; //사용매장
                    $data['use_no_partner'] = $validCouponCondition['use_no_partner'] ?? null; //사용매장
                    $data['use_at_discount'] = $validCouponCondition['use_at_discount'] ?? 0; //사용할인가격
                    $data['cd_disc_type'] = $coupon['cd_disc_type']; //쿠폰할인구분
                    $data['kind'] = $coupon['kind']; //쿠폰할인구분

                    $data['no_user'] = $coupon['no_user'];
                    $data['no_partner'] = $coupon['no_partner'];
                    $data['use_coupon_yn'] = $coupon['use_coupon_yn'];
                    $data['use_disc_type'] = $coupon['use_disc_type'];
                    $data['at_disct_money'] = $coupon['at_disct_money'];
                    $data['at_limit_money'] = $coupon['at_limit_money'];
                    $data['cd_payment_card'] = $coupon['cd_payment_card'];
                    $data['cd_mcp_status'] = $coupon['cd_mcp_status'];
                    $data['cd_cpe_status'] = $coupon['cd_cpe_status'];
                    $data['dt_reg'] = $coupon['dt_reg'];
                    $data['ds_pin'] = Common::getImagePath($coupon['ds_pin']);

                    $result[] = $data;
                }
            }
        }

        return $result;
    }

    /**
     * 쿠폰 조건조회
     *
     * @param array $eventNos
     *
     * @return array
     */
    public static function getCouponCondition(array $eventNos = array())
    {
        if ($eventNos) {
            $couponConditions = CouponEventCondition::whereIn('no_event', $eventNos)->get();
            $result = [];
            foreach ($couponConditions as $condition) {
                $result[$condition['no_event']][$condition['cd_cpn_condi_type']][] = $condition['ds_target'];
            }

            return $result;
        }
        return [];
    }

    /**
     * [주유] 쿠폰조건조회 - (카테고리 조건제외)
     *
     * @param array $coupon
     * @param array|null $condition
     * @param array|null $couponOrder
     *
     * @return array
     */
    public static function validCouponCondition(
        array $coupon,
        ?array $condition = null,
        ?array $couponOrder = null
    ): array {
        $validCondition = [];

        // 브랜드 제한 조건일 경우
        if ($coupon['yn_condi_status_partner'] === 'Y'
            && is_array($condition['125100'])
            && ($couponOrder && (! $couponOrder['no_partner']
                    || ! in_array($couponOrder['no_partner'], $condition['125100']))
            )
        ) {
            $validCondition[] = "125100";
        }

        // 매장 제한 조건일 경우
        if ($coupon['yn_condi_status_shop'] === 'Y'
            && is_array($condition['125200'])
            && ($couponOrder && (! $couponOrder['no_shop']
                    || ! in_array($couponOrder['no_shop'], $condition['125200']))
            )
        ) {
            $validCondition[] = "125200";
        }

        // 카드 보유 조건일 경우
        if ($coupon['yn_condi_status_shop'] === 'Y'
            && is_array($condition['125300'])
        ) {
            $isEmpty = 'Y';
            if (count($couponOrder['list_card'])) {
                foreach ($couponOrder['list_card'] as $card) {
                    if (in_array($card['cd_card_corp'], $condition['125300'])) {
                        $isEmpty = 'N';
                    }
                }
            } else {
                $isEmpty = 'N';
            }

            if ($isEmpty === 'Y') {
                $validCondition[] = "125300";
            }
        }

        // 요일 조건일 경우
        if ($coupon['yn_condi_status_weekday'] === 'Y'
            && is_array($condition['125400'])
            && !in_array(date('w'), $condition['125400'])
        ) {
            $validCondition[] = "125400";
        }

        // 품목 조건일 경우
        if ($coupon['yn_condi_status_menu'] == 'Y' && is_array($condition['125600'])) {
            $isEmpty = 'Y';
            if ($couponOrder && count($couponOrder['list_product'])) {
                foreach ($couponOrder['list_product'] as $product) {
                    if (in_array($product['no_product'], $condition['125600'])) {
                        $isEmpty = 'N';
                    }
                }
            } else {
                $isEmpty = 'N';
            }
            if ($isEmpty === 'Y') {
                $validCondition[] = "125600";
            }
        }

        $useNoShop = $condition['125200'][0];
        if ($useNoShop) {
            $useNoPartner = substr($condition['125200'][0], 0, 4);
        } elseif ($condition['125100'][0]) {
            $useNoPartner = $condition['125100'][0];
        } else {
            $useNoPartner = "";
        }

        $ynUse = (!$useNoPartner || ($couponOrder && $useNoPartner !== $couponOrder['no_partner'])) ? 'N' : 'Y';

        return [
            'use_no_shop' => $useNoShop,
            'use_no_partner' => $useNoPartner,
            'yn_use' => $ynUse,
            'error_condition' => implode(', ', $validCondition)
        ];
    }

    public static function getCouponViewTxt(
        $coupon,
        $cdBizKind,
        $noProductCart = [],
        $couponOrder = null
    ): array {
        $cdDiscType = [
            '126100' => '할인',
            '126200' => '% 할인',
            '126300' => '품목증정'
        ];

        $result = [];

        // 쿠폰종류별 쿠폰타입:: ds_discount
        if ($coupon['at_discount'] === '100'
            && $coupon['cd_disc_type'] === '126200') {
            //100%할인율 (ex 전액할인)
            $result['ds_discount'] = '전액할인';
        } elseif ($coupon['cd_disc_type'] === '126300') {
            //증정품(ex 라떼 지급)
            $result['ds_discount'] = $coupon['nm_product'] . ' 지급';
        } elseif ($coupon['cd_disc_type'] === '126100') {
            //할인금액(ex 1000원 할인)
            $result['ds_discount'] = '￦' . number_format((float)$coupon['at_discount']) . $cdDiscType[$coupon['cd_disc_type']];
        } else {
            //할인율 (ex  30% 할인)
            $result['ds_discount'] = $coupon['at_discount'] . $cdDiscType[$coupon['cd_disc_type']];
        }

        // 주유소 쿠폰일경우
        if ($coupon['kind'] == "9002") {
            //할인금액(ex 1000원 할인)
            $result['ds_discount'] = '￦' . number_format($coupon['at_discount']) . $cdDiscType[$coupon['cd_disc_type']] . "할인";
        }

        ##  쿠폰사용조건 ::ds_condition :: ds_product :: dt_expire
        if ($coupon['ds_etc']) {
            $arrDsEtc = explode('|', $coupon['ds_etc']);
            $shopTxt  = "";

            $shopTxt = head($arrDsEtc) ?? $shopTxt . "전매장";
            if (isset($arrDsEtc[1])) {
                $shopTxt .= "({$arrDsEtc[1]})";
            }

            ## 사용가능 브랜드, 매장
            $result['ds_condition'] = $shopTxt;
            if ($cdBizKind === Code::conf('biz_kind.oil')
                || $coupon['kind'] === '9002') {
                $result['ds_condition'] = "GS칼텍스" . $shopTxt;
            }

            ## 사용가능 구매조건
            $dsProductTxt = "";
            if (isset($arrDsEtc[4]) || isset($arrDsEtc[5]) || isset($arrDsEtc[6])) {
                if (isset($arrDsEtc[4])) {
                    $dsProductTxt .= $arrDsEtc[4] . "상품  ";
                }
                if (isset($arrDsEtc[5])) {
                    $dsProductTxt .= $arrDsEtc[5];
                }
                if (isset($arrDsEtc[6])) {
                    $dsProductTxt .= '￦' . number_format((float)$arrDsEtc[6]) . "이상 ";
                }
                $dsProductTxt .= " 구매시";
            }

            ## 사용가능 브랜드, 매장
            $result['ds_product'] = $dsProductTxt;
            if (($cdBizKind == Code::conf('biz_kind.oil')
                    || $coupon['kind'] == "9002") && $coupon['at_limit_money']) {
                $result['ds_product'] = '￦' . number_format($coupon['at_limit_money']) . " 주유시";
            }
        }

        ## 쿠폰 사용가능일 -- 만료일 // 시작일
        $today = date('Y-m-d');
        // 쿠폰 사용시작일 - 주유적용
        $timestamp = Carbon::parse($coupon['dt_use_start'])->timestamp;
        $dsUseStart = date("Y/m/d", $timestamp);
        $result['dt_use_start'] = '';
        if ($today < $coupon['dt_use_start']) {
            $result['dt_use_start'] = $dsUseStart . " 부터 사용가능";
        }

        //쿠폰 사용만료일
        $timestamp = Carbon::parse($coupon['dt_expire'])->timestamp;
        $dtExpire = date("Y/m/d", $timestamp);
        $result['dt_expire'] = match ($dtExpire) {
            '2999/12/31', '1970/01/01' => '만료일 없음',
            default => $dtExpire . ' 까지'
        };

        ##  쿠폰사용처
        $result['nm_partner'] = "전체";
        if ((isset($coupon['yn_condi_status_partner']) && $coupon['yn_condi_status_partner'] === "Y")
            || (isset($coupon['yn_condi_status_shop']) && $coupon['yn_condi_status_shop'] === "Y")) {
            if ($coupon['memberEventCondition']) {
                $result['nm_partner'] = $coupon['memberEventCondition']['nm_partner'];
            }
        }

        ## 품목증정 쿠폰인경우
        $result['at_discount'] = 0;
        if ($coupon['cd_disc_type'] === '126300' && $noProductCart) {
            if (is_array($noProductCart)) {
                // 장바구니에 품목증정을 넘어온경우
                $result['at_discount'] = in_array($coupon['no_product'], $noProductCart) ? $coupon['at_discount'] : 0;
            } else {
                $result['at_discount'] = $coupon['no_product'] === $noProductCart ? $coupon['at_discount'] : 0;
            }
        } else {
            $result['at_discount'] = self::getDiscountMoney($coupon, $couponOrder);
        }

        if (isset($coupon['partner']) && $coupon['partner']['ds_pin']) {
            $result['ds_pin'] = Common::getImagePath($coupon['partner']['ds_pin']);
        }

        $result['ds_pin_partner'] = $coupon['yn_condi_status_partner'];
        $result['ds_pin_shop'] = $coupon['yn_condi_status_shop'];

        $result['nm_partner'] = $coupon['partner']['nm_partner']; // 브랜드명
        $result['cd_disc_type'] = $coupon['cd_disc_type']; //	쿠폰할인구분
        $result['cd_mcp_status'] = $coupon['cd_mcp_status'];

        return $result;
    }

    /**
     * 할인되는 금액반환
     *
     * @param array $coupon
     * @param array|null $couponOrder
     *
     * @return float|int
     */
    public static function getDiscountMoney(
        MemberPartnerCoupon $coupon,
        ?array $couponOrder = null
    ): float|int {
        $atDiscount = 0;
        if ($coupon['cd_disc_type'] == '126100') {
            // 금액할인
            $atDiscount = $coupon['at_discount'];
        } elseif ($coupon['cd_disc_type'] == '126200') {
            // 할인율할인
            if ($couponOrder['at_price_total']) {
                $atDiscount = Common::getDiscountRate($couponOrder['at_price_total'], $coupon['at_discount']);
            }

            if ($coupon['at_max_disc'] && $atDiscount > $coupon['at_max_disc']) {
                // 최대할인금액 초과시
                $atDiscount = $coupon['at_max_disc'];
            }
        } elseif ($coupon['cd_disc_type'] == '126300') {
            // 품목증정
        }

        return $atDiscount;
    }


    /**
     *  GS 임시쿠폰정보 반환
     *
     * @param string $couponNo
     *
     * @return GsCpnEvent|null
     */
    public static function partnerCouponTempInfo(string $couponNo): ?GsCpnEvent
    {
        return GsCpnEvent::select([
            'gs_cpn_event.no_part_cpn_event',
            'gs_cpn_event.ds_cpn_title',
            'gs_cpn_event.cdn_cpn_amt',
            'member_partner_coupon.ds_cpn_no_internal',
            'member_partner_coupon.ds_cpn_no',
            'member_partner_coupon.no_event',
            'member_partner_coupon.yn_real_pubs',
        ])->where('member_partner_coupon.ds_cpn_no', $couponNo)
            ->leftJoin(
                'member_partner_coupon',
                'no_event',
                '=',
                'no_part_cpn_event'
            )
            ->first();
    }

    public static function gsCouponIssue($noUser, $couponNo, $codeType)
    {
        $paymentNo = "owin" . date('YmdHis') . $noUser;
        $response = GsService::issue($codeType, $paymentNo);

        if ($response['returnCode'] == '00000') {
            // 조회
            $coupon = GsService::search($codeType, $response['couponInfo']['cupn_No']);
            if ($coupon && $coupon['returnCode'] == '00000') {
                self::updateMemberPartnerCoupon([
                    'ds_cpn_no' => $coupon['couponInfo']['CUPN_NO'],
                    'at_disct_money' => $coupon['couponInfo']['FAMT_AMT'],
                    'dt_start_from_made' => date('Y-m-d 00:00:00', strtotime($coupon['couponInfo']['AVL_START_DY'])),
                    'dt_end_from_made' => date('Y-m-d 23:59:59', strtotime($coupon['couponInfo']['AVL_END_DY'])),
                    'ds_isssue_code_frm_part' => $response['Issu_Req_Val'],
                    'yn_real_pubs' => 'Y',
                ], [
                    'ds_cpn_no' => $couponNo
                ]);
            }

            return [
                'returnCode' => $response['returnCode'],
                'ds_cpn_no' => $coupon['couponInfo']['CUPN_NO'],
            ];
        }
        return null;
    }

    public static function updateMemberPartnerCoupon($update, $where)
    {
        MemberPartnerCoupon::where($where)->update($update);
    }

    public static function useMemberPartnerCoupon($noOrder, $noUser, $couponNos)
    {
        MemberPartnerCoupon::where([
            'no_user' => $noUser,
            'cd_mcp_status' => '122100'
        ])->whereIn('no', $couponNos)->update([
            'use_coupon_yn' => 'N',
            'cd_mcp_status' => '122200',
            'cd_cpe_status' => '121200',
            'cd_payment_status' => '603100',
            'no_order' => $noOrder,
        ]);
    }
}
