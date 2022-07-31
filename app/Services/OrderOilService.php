<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AppType;
use App\Enums\BookingTypeCode;
use App\Enums\EnumYN;
use App\Enums\GasKind;
use App\Enums\ServicePayCode;
use App\Exceptions\OwinException;
use App\Exceptions\TMapException;
use App\Models\CouponEvent;
use App\Models\MemberCoupon;
use App\Models\OrderList;
use App\Models\OrderLocation;
use App\Models\OrderPayment;
use App\Models\OrderProcess;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Utils\Code;
use App\Utils\Oil;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderOilService extends Service
{

    /**
     * [주유] 주유결제후 - 쿠폰 발급
     * @param int $noUser
     * @return void
     * @throws OwinException
     */
    public static function getReceiveCouponAfterPay(int $noUser): void
    {
        try {
            DB::beginTransaction();
            //주유 결제 후 발행할 쿠폰
            $coupons = CouponEvent::select([
                'no_event',
                'nm_event',
                'yn_condi_status_partner',
                'yn_condi_status_shop',
                'yn_condi_status_card',
                'yn_condi_status_weekday',
                'yn_condi_status_menu',
                'yn_condi_status_money',
                'cd_disc_type',
                DB::raw(
                    "CASE
				WHEN cd_disc_type = '126300' THEN (SELECT at_price FROM product WHERE no_product = at_discount )
				ELSE at_discount
			  END AS at_discount"
                ),
                DB::raw("at_discount AS no_product"),
                'at_max_disc',
                'dt_expire',
                'at_limit_count',
                'yn_dupl_use',
                'at_pub_count',
                'ds_etc',
                DB::raw(
                    "IF ( (cd_disc_type='126300'), (SELECT nm_product FROM product WHERE no_product = at_discount), '' )AS nm_product"
                ),
                DB::raw(
                    "IF(yn_condi_status_partner = 'Y', (SELECT ds_target FROM  coupon_event_condition   WHERE coupon_event_condition.no_event  = coupon_event.no_event AND cd_cpn_condi_type = '125100') , 0)AS t_partner"
                ),
                DB::raw(
                    "IF(yn_condi_status_money = 'Y', (SELECT ds_target FROM  coupon_event_condition   WHERE coupon_event_condition.no_event  = coupon_event.no_event AND cd_cpn_condi_type = '125700') , 0)AS t_money"
                ),
            ])->where([
                ['yn_condi_status_partner', '=', 'Y'],
                ['yn_condi_status_money', '=', 'Y'],
                ['cd_disc_type', '=', '126300'],
                ['cd_cpe_status', '=', '121100'],
                ['at_limit_count', '>', 'at_pub_count'],
            ])->orderByDesc('t_money')->get();

            $couponIds = $coupons->pluck('no_event')->all();

            if ($couponIds) {
                //사용자에게 발행된 쿠폰 체크
                $memberCouponIds = MemberCoupon::where('no_user', $noUser)->whereIn('no_event', $couponIds)->get()->pluck('no_event')->all();
                if (count($memberCouponIds)) {
                    $coupons = $coupons->filter(function ($q) use ($memberCouponIds) {
                        return !in_array($q->no_event, $memberCouponIds);
                    });
                }
            }

            $memberCouponBody = [];
            foreach ($coupons as $coupon) {
                $memberCouponBody[] = [
                    'no_user' => $noUser,
                    'no_event' => $coupon['no_event'],
                    'cd_mcp_status' => '122100',
                ];
                CouponEvent::where('no_event', $coupon['no_event'])->increment('at_pub_count');
            }

            MemberCoupon::insert($memberCouponBody);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::channel('error')->error('[P2328] getReceiveCouponAfterPay error', [$e->getMessage()]);
            throw new OwinException(Code::message('P2328'));
        }
    }

    public function reservation(User $user, Shop $shop, Collection $request, string $appType = null): array
    {
        $verify = $this->verifyOilOrder($user, $shop, $request, $appType);

        $noOrder = (new OrderService())->getMaxOrderNo($shop->no_shop);
        $noPayment = makePaymentNo();

        $nmOrder = $verify['shopProducts']->first()->nm_product;

        $cdBookingType = empty(data_get($request, 'cd_booking_type')) == false ? BookingTypeCode::case(
            $request->get('cd_booking_type')
        )->value : '505300';

        try {
            ##  현장결제 상태값 확인후 세팅 (현장주문)

            // 주유소 최근도착 시간 반환 INTIME
            // 현재시간 과 비교 주유소내 있을경우 픽업상태값 준비완료로 설정 (cd_pickup_status - 602300)
            $enterInfo = OrderService::getMemberShopEnterLog([
                'no_user' => $user->no_user,
                'no_shop' => $shop->no_shop,
                'yn_is_in' => 'Y'
            ]);

            $cdPickupStatus = '602200'; //주문접수 (주문예약)
            $ynGasPreOrder = 'N';
            if ($enterInfo) {
                $minInTime = strtotime($enterInfo['dt_reg']);
                $maxInTime = strtotime(
                    "+" . Code::conf('oil.arrival_check_time') . " minutes",
                    strtotime($enterInfo['dt_reg'])
                );
                $now = strtotime(date('Ymd H:i:s'));

                // 현장주문 - 바로승인처리
                if ($minInTime < $now && $now < $maxInTime) {
                    $cdPickupStatus = '602300'; //2분이내 - 상품준비완료(주유대기)
                    $ynGasPreOrder = "Y"; //현장주문상태
                }
            }

            $orderPayment = new OrderPayment([
                'no_order' => $noOrder,
                'no_payment' => $noPayment,
                'no_partner' => $shop->no_partner,
                'no_shop' => $shop->no_shop,
                'no_user' => $user->no_user,
                'cd_pg' => $shop->cd_pg,
                'cd_payment' => '501200',
//            'cd_payment_kind' => '',
                'cd_payment_status' => '603100',
                'cd_pg_result' => 604050,
                'at_price' => $request['at_price'],
                'at_price_pg' => 0,
                'cd_card_corp' => $verify['card']->cd_card_corp,
                'no_card' => $verify['card']->no_card,
                'no_card_user' => $verify['card']->no_card_user,
                'product_num' => 1,
            ]);
            $orderPayment->saveOrFail();

            $dsCpnNo = "";
            if (empty(data_get($request, 'discount_info.coupon.no')) == false) {
                $dsCpnNo = data_get($request, 'discount_info.coupon.no');
            } elseif (empty(data_get($request, 'list_no_event')) == false) {
                $dsCpnNo = data_get($request, 'list_no_event')[0];
            }
            $dsCarNumber = "";
            if (empty(data_get($request, 'car_number')) == false) {
                $dsCarNumber = data_get($request, 'car_number');
            } else {
                $dsCarNumber = $user->memberCarInfo?->ds_car_number;
            }

            $ynGasOrderLiter = false;
            if (empty(data_get($request, 'order_type')) == false) {
                $ynGasOrderLiter = $request['order_type'] == 'LITER';
            } elseif (empty(data_get($request, 'yn_gas_order_liter')) == false) {
                $ynGasOrderLiter = $request['yn_gas_order_liter'] == '1';
            }

            $orderList = (new OrderList([
                'no_order' => $noOrder,
                'no_payment_last' => $noPayment,
                'nm_order' => $nmOrder,
                'no_user' => $user->no_user,
                'no_device' => '',
                'ds_adver' => '',
                'no_partner' => $shop->no_partner,
                'no_shop' => $shop->no_shop,
                'cd_service' => '900100',
                'cd_service_pay' => getAppType()->value == AppType::TMAP_AUTO->value ? $request['cd_service_pay'] : ServicePayCode::case($request['cd_service_pay']),
                'cd_calc_status' => '609100',
                'cd_send_status' => '610100',
                'no_card' => $verify['card']->no_card,
                'at_price' => $request['at_price'],
                'at_lat_decide' => $shop->at_lat,
                'at_lng_decide' => $shop->at_lng,
                'dt_pickup' => Carbon::now()->format('Y-m-d 23:59:59'),
                'yn_gps_status' => 'Y',
                'cd_alarm_event_type' => '607000',
                'cd_call_shop' => '611100',
                'cd_payment' => '501200',
                'cd_payment_status' => $orderPayment->cd_payment_status,
                'cd_order_status' => '601200',
                'cd_pickup_status' => $cdPickupStatus,
                'at_cpn_disct' => empty(data_get($request, 'at_cpn_disct')) == false ? $request['at_cpn_disct'] : 0,
                'at_point_disct' => empty(data_get($request, 'at_point_disct')) == false ? $request['at_point_disct'] : 0,
                'at_price_pg' => 0,
                'cd_gas_kind' => $appType == 'tmap' ? $request['cd_gas_kind'] : GasKind::case($request['cd_gas_kind'])->value,
                'at_gas_price' => $request['at_gas_price'],
                'at_gas_price_opnet' => $request['at_gas_price'], //todo
                'cd_pg' => $shop->cd_pg,
                'at_liter_gas' => $request['at_liter_gas'],
                'yn_gas_order_liter' => $ynGasOrderLiter,
                'yn_gas_pre_order' => $ynGasPreOrder,
                'ds_cpn_no' => $dsCpnNo,
                'id_pointcard' => $verify['pointCard']?->id_pointcard,
                'ds_franchise_num' => $shop->shopDetail->ds_franchise_num,
                'cd_booking_type' => '505300',
                'ds_car_number' => $dsCarNumber,
                'seq' => $user->memberCarInfoAll->where(
                    'ds_car_number',
                    $dsCarNumber
                )->first()?->seq,
                'cd_third_party' => $appType == 'tmap' ?: '110400',
                'cd_payment_method' => '504100',
                'dt_pickup_status' => now(),
                'dt_order_status' => now(),
                'dt_payment_status' => now(),
            ]));
            $orderList->saveOrFail();
            $orderPayment->update([
                'at_pg_commission_rate' => $shop->at_pg_commission_rate,
                'cd_commission_type' => $shop->cd_commission_type,
                'at_commission_amount' => $shop->at_commission_amount,
                'at_commission_rate' => $shop->at_commission_rate,
                'at_sales_commission_rate' => $shop->at_sales_commission_rate,
            ]);
            (new OrderProcess([
                'cd_order_process' => '616100',
                'no_user' => $user->no_user,
                'no_order' => $noOrder,
                'no_shop' => $shop->no_shop,
            ]))->saveOrFail();

            (new OrderLocation([
                'no_order' => $noOrder
            ]))->saveOrFail();
            $noOrderProduct = time() . mt_rand(1000, 9999);
            $this->setOilOrderProduct($noOrder, $user->no_user, $noOrderProduct, $request, $verify['shopProducts']);

            (new MemberService())->updateMember([
                'cd_booking_type' => $cdBookingType
            ], [
                'no_user' => $user->no_user
            ]);
        } catch (Throwable $t) {
            Log::channel('error')->error(
                $t->getMessage(),
                [$t->getFile(), $t->getLine(), $t->getTraceAsString()]
            );

            return [
                'result' => false,
                'no_order' => $noOrder,
                'nm_order' => $nmOrder,
                'pg_msg' => $t->getMessage(),
                'msg' => Code::message('P2010')
            ];
        }

        return [
            'result' => true,
            'no_order' => $noOrder,
            'nm_order' => $nmOrder,
        ];
    }

    public function verifyOilOrder(
        User $user,
        Shop $shop,
        Collection $request,
        string $appType = null
    ): array {
//        $isTmap = getAppType()->value == AppType::TMAP_AUTO->value;
        $isTmap = $appType == 'tmap' ;
        $cdGasKind = $isTmap ? $request['cd_gas_kind'] : GasKind::case($request['cd_gas_kind'])->value;

        $shopProducts = Product::where([
            'no_partner' => $shop['no_partner'],
            'ds_status' => EnumYN::Y->name,
            'no_product' => $shop['no_partner'] . Code::conf("gas_kind_no_product.{$cdGasKind}"),
        ])->get();

        //주문불가 - 11시 45분부터 12시까지 주유 예약진행 체크
        $chkDate = date('H') * 60 + date('i');
        $unOrderTime = Code::conf('oil.unorder_hour') * 60 + Code::conf('oil.unorder_minute') - 1;
        if ($chkDate > $unOrderTime && $chkDate < 1439) {
            throw new OwinException(
                str_replace(
                    ':UNUSE_HOUR:',
                    (string)Code::conf('oil.unorder_hour'),
                    str_replace(':UNUSE_MINUTE:', (string)Code::conf('oil.unorder_minute'), Code::message('P2812'))
                )
            );
        }

        //100원 단위의 주문이 아닐 경우
//        if (!is_int($request['at_price'] / 100)) {
//            throw new OwinException(Code::message('P2025'));
//        }

        // 주유주문금액이 10,000원미만일 경우 주유가능 min값 설정
        if ($request['at_price'] < 10000) {
            if ($isTmap) {
                throw new TMapException('P2022', 400);
            }
            throw new OwinException(Code::message('P2022'));
        }

        //carId 주문일 경우 beacon 없으면 error
        //todo carId 주문 사용하는지 확인 필요
        if (empty(data_get($request, 'cd_booking_type')) == false && $request['cd_booking_type'] === '505100' && !count(Auth::user()->beaconCount)) {
            if ($isTmap) {
                throw new TMapException('P3002', 400);
            }
            throw new OwinException(Code::message('P3002'));
        }

        $shopBookingTypes = $shop['list_cd_booking_type'] ? explode(',', $shop['list_cd_booking_type']) : null;
        if (!$shopBookingTypes || !count($shopBookingTypes)) {
            if ($isTmap) {
                throw new TMapException('P3000', 400);
            }
            throw new OwinException(Code::message('P3000'));
        }

        $cdBookingType = empty(data_get($request, 'cd_booking_type')) == false ? BookingTypeCode::case(
            $request->get('cd_booking_type')
        )->value : '505100';
        if (!in_array($cdBookingType, $shopBookingTypes)) {
            if ($isTmap) {
                throw new TMapException('P3001', 400);
            }
            throw new OwinException(Code::message('P3001'));
        }

        //카드 정보 조회
        $unUseCards = array_unique(ShopService::getShopUnUseCards($shop['no_shop'])->pluck('cd_card_corp')->all());
        $noCard = $request['no_card'];
        $card = $user->memberCard->filter(function ($query) use ($noCard, $unUseCards) {
            return !in_array(
                    $query['cd_card_corp'],
                    $unUseCards
                ) && $query['cd_pg'] === '500100' && $noCard === $query['no_card'];
        })->whenEmpty(function () use ($isTmap) {
            if ($isTmap) {
                throw new TMapException('P1033', 400);
            }
            throw new OwinException(Code::message('P1033'));
        })->first();

        // 오늘 해당 주유소 주유주문건 정보
        if (OilService::getTodayOrderCnt($user->no_user, $shop->no_shop)) {
            if ($isTmap) {
                throw new TMapException('P2810', 400);
            }
            throw new OwinException(Code::message('P2810'));
        }

        //기본 할인 금액이 다를 경우 오류
        if (isset($request['at_disct']) && intval($request['at_disct']) != intval(Code::conf('oil.oil_disct'))) {
            if ($isTmap) {
                throw new TMapException('P2201', 400);
            }
            throw new OwinException(Code::message('P2201'));
        }

        //러쉬 결제일 경우 오류
        if ($request['cd_service_pay'] == 'LUSH') {
            if ($isTmap) {
                throw new TMapException('C0504', 400);
            }
            throw new OwinException(Code::message('C0504'));
        }

        if (empty(data_get($request, 'order_type')) == false) {
            if (($request['order_type'] === 'LITER' && Oil::getOilPriceCalculate($request['at_liter_gas'], $request['at_gas_price']) != $request['at_price'])
                || ($request['order_type'] === 'PRICE' && floor(Oil::getLiterCalculate($request['at_price'], $request['at_liter_gas'])) != $request['at_gas_price'])) {
                throw new OwinException(Code::message('P2204'));
            }
        }

        if (empty(data_get($request, 'yn_gas_order_liter'))  == false) {
            if (($request['yn_gas_order_liter'] === '1' && Oil::getOilPriceCalculate(floatval($request['at_liter_gas']), floatval($request['at_gas_price'])) != $request['at_price'])
            || ($request['yn_gas_order_liter'] === '0' && floor(Oil::getLiterCalculate(intval($request['at_price']), floatval($request['at_liter_gas']))) != $request['at_gas_price'])) {
                throw new TMapException('P2204', 400);
            }
        }

//        쿠폰 체크
        if (empty(data_get($request, 'at_cpn_disct')) == false && $request['at_cpn_disct'] > 0) {
            $coupons = (new CouponService())->getOilUsableCoupon(
                $user->no_user,
                $shop->no_shop,
                intval($request['at_price']),
                intval($request['at_liter_gas']),
                $card
            );

            if (empty(data_get($request, 'discount_info.coupon.no')) === false) {
                if (data_get($request, 'discount_info.coupon.at_coupon') != $request['at_cpn_disct']) {
                    throw new OwinException(Code::message('P2330'));
                }

                $coupons = $coupons->where('no', Arr::get($request, 'discount_info.coupon.no'))
                    ->whenEmpty(function () {
                        throw new OwinException(Code::message('P2300'));
                    })->whenNotEmpty(function ($coupon) use ($request) {
                        if ($coupon->first()['at_discount'] != Arr::get($request, 'discount_info.coupon.at_coupon')
                            || $coupon->first()['at_discount'] != $request['at_cpn_disct']) {
                            throw new OwinException(Code::message('P2330'));
                        }
                    });
            } elseif (empty(data_get($request, 'list_no_event')) == false) {
                $coupons = $coupons->whereIn('no', array_values(array_filter($request['list_no_event'], function ($value) {
                    return $value !== '0';
                })))->whenEmpty(function () {
                    throw new TMapException('P2300', 400);
                })->whenNotEmpty(function ($coupon) use ($request) {
                    if ($coupon->first()['at_discount'] != $request['at_cpn_disct']) {
                        throw new TMapException('P2330', 400);
                    }
                });
            } else {
                $isTmap ?
                    throw new TMapException('P2300', 400)
                    : throw new OwinException(Code::message('P2300'));
            }

            $coupons->whenNotEmpty(function ($coupon) use ($user, $isTmap) {
                if ($coupon->first()['ds_cpn_no_internal'] && $coupon->first()['yn_real_pubs'] == 'N') {
                    // GS 임시쿠폰정보 반환
                    $partnerCouponTempInfo = CouponService::partnerCouponTempInfo($coupon->first()['ds_cpn_no']);
                    if ($partnerCouponTempInfo) {
                        $couponResult = CouponService::gsCouponIssue(
                            $user->no_user,
                            $coupon->first()['ds_cpn_no'],
                            $partnerCouponTempInfo['cdn_cpn_amt']
                        );

                        if (!$couponResult || $couponResult['returnCode'] != '00000') {
                            if ($isTmap) {
                                throw new TMapException('P2390', 400);
                            }
                            throw new OwinException(Code::message('P2390'));
                        }
                    }
                }
            });
        }

        $pointCard = null;
//        포인트 카드 할인 체크
        if (empty(data_get($request, 'at_point_disct')) == false && $request['at_point_disct'] > 0) {
            if (empty(data_get($request, 'discount_info.point_card.id')) == false) {
                $pointCard = $user->memberPointCard->filter(function ($query) use ($request) {
                    return $query['id_pointcard'] == $request['discount_info']['point_card']['id'];
                })->first();
            } elseif ($isTmap) {
                $pointCard = $user->memberPointCard->first();
            }

            if (!$pointCard) {
                $isTmap ?
                    throw new TMapException('P2208', 400)
                    : throw new OwinException(Code::message('P2208'));

            }

            if ($pointCard['yn_sale_card'] == 'Y') {
                if ($pointCard['gsSaleCard']['yn_can_save'] != 'Y') {
                    $isTmap ?
                        throw new TMapException('P2208', 400)
                        : throw new OwinException(Code::message('P2208'));
                }
                $atCpnDisct = empty(data_get($request, 'at_cpn_disct')) == false ? $request['at_cpn_disct'] : 0;
                $atPointDisct = round(($request['at_price'] - $atCpnDisct) / $request['at_liter_gas']) * ($request['discount_info']['point_card']['at_disct_price'] ?? 0);
                if ($pointCard['gsSaleCard']['at_can_save_amt'] < $atPointDisct) {
                    $atPointDisct = $pointCard['gsSaleCard']['at_can_save_amt'];
                }

                if ($request['at_point_disct'] != $atPointDisct) {
                    $isTmap ?
                        throw new TMapException('P2208', 400)
                        : throw new OwinException(Code::message('P2208'));
                }
            }
        }

        return [
            'shopProducts' => $shopProducts,
            'card' => $card,
            'pointCard' => $pointCard,
        ];
    }

    public function setOilOrderProduct(
        string $noOrder,
        int $noUser,
        string $noOrderProduct,
        Collection $request,
        Collection $shopProducts
    ): void {
        if (empty(data_get($request, 'at_cpn_disct')) == false && $request['at_cpn_disct'] > 0) {
            CouponService::useMemberPartnerCoupon($noOrder, $noUser, [data_get($request, 'discount_info.coupon.no')]);
        }

        foreach ($shopProducts as $key => $product) {
            $orderProduct = (new OrderProduct([
                'no_order_product' => $noOrderProduct . str_pad((string)($key + 1), 4, '0', STR_PAD_LEFT),
                'no_order' => $noOrder,
                'no_product' => $product['no_product'],
                'nm_product' => $product['nm_product'],
                'no_user' => $noUser,
                'at_price' => $product['at_price'],
                'at_price_product' => $product['at_price'],
                'at_price_option' => 0,
                'ct_inven' => 1,
            ]));
            $orderProduct->saveOrFail();
        }
    }

    public function cancel(Collection $order, string $appType = null)
    {
        try {
            DB::beginTransaction();
            //주문상태 변경
            OrderList::where('no_order', $order['no_order'])->update([
                'cd_order_status' => '601900',
                'cd_pickup_status' => '602400',
                'cd_payment_status' => '603900',
                'dt_payment_status' => now(), // 결제상태 변경일시
                'dt_order_status' => now(), // 주문상태 변경일시
            ]);
            OrderPayment::where('no_payment', $order['no_payment_last'])->update([
                'dt_req_refund' => DB::raw('NOW()'),
                'dt_res_refund' => DB::raw("NOW()"),
            ]);
            OrderService::registOrderProcess([
                'no_user' => $order['no_user'],
                'no_order' => $order['no_order'],
                'no_shop' => $order['no_shop'],
                'cd_order_process' => '616991',
                'dt_order_process' => DB::raw("NOW()"),
            ]);

            if ($order['at_cpn_disct'] > 0) {
                (new CouponService())->refundMemberPartnerCoupon($order['no_order'], $order['no_user']);
            }
            DB::commit();

            return true;
        } catch (Throwable $t) {
            DB::rollBack();
            if ($appType == 'tmap') {
                throw new TMapException('P2110', 400);
            } else {
                throw new OwinException(Code::message('P2110') . $t->getMessage());
            }
        }
    }
}
