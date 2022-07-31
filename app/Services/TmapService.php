<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnumYN;
use App\Exceptions\TMapException;
use App\Models\OrderList;
use App\Models\Shop;
use App\Utils\Code;
use App\Utils\Common;
use App\Utils\Oil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;

use function now;

class TmapService extends Service
{
    /**
     *
     * @param Request $request
     *
     * @return NewAccessToken
     */
    public function authorization(Request $request): NewAccessToken
    {
        $memberInfo = MemberService::getMember([
            'ds_ci' => $request['ci']
        ])->whenEmpty(function () {
            throw new TMapException('M1514', 400);
        })->whenNotEmpty(function($collect) {
            if ($collect->first()->ds_status != EnumYN::Y->name) {
                throw new TMapException('M1302', 400); // 로그인 불가능한 상태입니다.
            }
            if (!count($collect->first()->memberCarInfoAll)) {
                throw new TMapException('M1510', 400);
            }
            if (!count($collect->first()->memberCard)) {
                throw new TMapException('P1021', 400);
            }
        })->first();

        $randRsm = strtotime("NOW") . mt_rand(100, 999);
        MemberService::updateMemberDetail([
            'dt_account_reg_rsm' => now(),
            'ds_access_token_rsm' => hash("sha256", "owinrsm_{$memberInfo['no_suer']}_{$randRsm}"),
            'yn_account_status_rsm' => 'Y',
            'cd_third_party' => '110400',
            'ds_last_login_ip' => $request->server('REMOTE_ADDR'),
            'dt_last_login' => now(),
            'ds_access_vin_rsm' => $request['no_vin'],
        ], [
            'no_user' => $memberInfo['no_user']
        ]);

        MemberService::updateMember([
            'cd_mem_level' => '104700'
        ], [
            'no_user' => $memberInfo['no_user'],
        ]);

        // 회원 인증시 비밀번호 암호화 다시 세팅해야됨 (bcrypt 사용)
        return MemberService::createAccessToken($memberInfo['no_user']);
    }

    public function getOrderDetail($noOrder, $user): array
    {
        return OrderList::where('no_order', $noOrder)
            ->with(['shop', 'partner', 'shop.shopOil', 'orderPayment', 'orderProduct.product', 'memberBenefitCoupon'])
            ->get()->whenEmpty(function () {
                throw new TMapException('P2040', 400);
            })->map(function ($order) use ($user){
                $bizKind = $order->partner->cd_biz_kind;
                $userLang = $user->memberDetail?->ds_nation == 'en' ?? 'ko';

                $ynPaymentCancel = 'N';
                if ($bizKind == '201300'
                    && now() < Carbon::createFromFormat('Y-m-d H:i:s', $order->dt_reg)->format('Y-m-d 23:30:00')) {
                    $ynPaymentCancel = 'Y';
                } elseif ($order->cd_pickup_status = '602100') {
                    $ynPaymentCancel = 'Y';
                } elseif ($order->cd_pickup_status == '602200' && $order->at_add_delay_min > 0
                    && (strtotime(date('Y-m-d H:i:s', $order->dt_pickup_status)) + 300) > time()) {
                    $ynPaymentCancel = 'Y';
                }

                $orderStatus = getOrderStatus(
                    $bizKind,
                    $order->cd_order_status,
                    $order->cd_pickup_status,
                    $order->cd_payment_status,
                    null,
                    $order->orderPayment?->cd_pg_result
                );

                return [
                    'result' => '1',
                    'cd_biz_kind' => $order->partner->cd_biz_kind,
                    'no_order_user' => substr($order->no_order, -7),
                    'nm_order' => $order->nm_order,
                    'cd_gas_kind' => $order->cd_gas_kind,
                    'at_price' => $order->at_price,
                    'at_disct' => intval($order->at_disct),
                    'at_cpn_disct' => intval($order->at_cpn_disct),
                    'at_point_disct' => intval($order->at_point_disct),
                    'at_cash_disct' => intval($order->at_cash_disct),
                    'at_event_cash_disct' => intval($order->at_event_cash_disct),
                    'at_bank_disct' => intval($order->at_bank_disct),
                    'cd_order_status' => $order->cd_order_status,
                    'cd_payment_status' => $order->cd_payment_status,
                    'cd_pickup_status' => $userLang ? '602200'
                        : ($order->cd_pickup_status == '602390' ? '602100'
                            : $order->cd_pickup_status),
                    'cd_alarm_event_type' => $order->cd_alarm_event_type,
                    'cd_call_shop' => $order->cd_call_shop,
                    'at_add_delay_min' => $order->at_add_delay_min,
                    'at_event_support' => intval($order->at_event_support),
                    'dt_pickup' => $bizKind == '201300' ? $order->cd_pickup_status < 602400
                        ? Carbon::createFromFormat('Y-m-d H:i:s', $order->dt_reg)->format('g:i A')
                        : Carbon::createFromFormat('Y-m-d H:i:s', $order->dt_pickup_status)->format('g:i A') : '',
                    'dt_order' => Carbon::createFromFormat('Y-m-d H:i:s', $order->dt_reg)->format('Y.m.d'),
                    'at_liter_gas' => $order->at_liter_gas,
                    'at_liter_real' => $order->at_liter_real,
                    'yn_gas_order_liter' => $order->yn_gas_order_liter,
                    'at_price_real_gas' => $order->at_price_real_gas,
                    'ds_unit_id' => $order->ds_unit_id,
                    'no_approval' => $order->no_approval,
                    'at_price_pg' => $order->at_price_pg,
                    'at_commission_rate' => $order->partner->at_commission_rate,
                    'ds_request_msg' => $order->ds_request_msg,
                    'ds_franchise_num' => $order->ds_franchise_num,
                    'at_p_point_for_add' => $order->at_p_point_for_add,
                    'yn_payment_cancel' => $ynPaymentCancel,
                    'no_partner' => intval($order->no_partner),
                    'no_shop' => intval($order->no_shop),
                    'nm_partner' => $order->partner?->nm_partner,
                    'nm_shop' => $order->shop?->nm_shop,
                    'at_lat' => $order->shop?->at_lat,
                    'at_lng' => $order->shop?->at_lng,
                    'ds_address' => $order->shop?->ds_address . $order->shop?->ds_address2,
                    'ds_tel' => $order->shop?->shopOil?->ds_tel,
                    'ds_biz_num' => $order->shop?->shopDetail?->ds_biz_num,
                    'ds_new_adr' => $order->shop?->shopOil?->ds_new_adr,
                    'nm_os' => $order->shop?->shopOil?->nm_os,
                    'nm_owner' => $order->shop?->shopDetail?->nm_owner,
                    'cd_reject_reason' => $order->orderPayment->cd_reject_reason,
                    'nm_reject_reason' => match (empty($order->orderPayment->cd_reject_reason)) {
                        true => null,
                        default => CodeService::getCode($order->orderPayment->cd_reject_reason)->nm_code
                    },
                    'cd_pg_result' => $order->orderPayment->cd_pg_result,
                    'nm_pg_result' => match (empty($order->orderPayment->cd_pg_result)) {
                        true => null,
                        default => CodeService::getCode($order->orderPayment->cd_pg_result)->nm_code
                    },
                    'nm_card_corp' => CodeService::getCode($order->orderPayment->cd_card_corp)->nm_code,
                    'no_card_user' => (string)$order->orderPayment->no_card_user,
                    'cd_status' => $orderStatus ? $orderStatus[0] : '',
                    'nm_status' => $orderStatus ? $orderStatus[1] : '',
                    'cd_booking_type' => $order->cd_booking_type,
                    'ct_order' => $order->orderProduct->count(),
                    'list_order_product' => $order->orderProduct->map(function ($product) use ($order) {
                        return [
                            'no_order_product' => (string)$product['no_order_product'],
                            'no_product' => (string)$product['no_product'],
                            'nm_product' => $product->product?->nm_product,
                            'ct_inven' => (string)$product['ct_inven'],
                            'at_price' => $product['at_price'],
                            'ds_image_path' => Common::getImagePath($product->product?->ds_image_path),
                            'nm_sel_group1' => (string)$product['nm_sel_group1'],
                            'nm_sel_option1' => (string)$product['nm_sel_option1'],
                            'at_gas_price' => $order['at_gas_price'],
                            'at_liter_real' => $order['at_liter_real'],
                        ];
                    }),
                    'yn_self' => $order->shop->shopOil->yn_self,
                    'guide_deatil_image' => match ($order->shop->shopOil->yn_dp2) {
                        'Y' => match ($order->shop->shopDetail->yn_self) {
                            'Y' => 'http://app.owin.kr/data2/owin/guide_image_dp2_self.png',
                            default => 'http://app.owin.kr/data2/owin/guide_image_dp2_full.png'
                        },
                        default => match ($order->shop->shopDetail->yn_self) {
                            'Y' => 'http://app.owin.kr/data2/owin/guide_image_dp1_self.png',
                            default => 'http://app.owin.kr/data2/owin/guide_image_dp1_full.png'
                        }
                    },
                    'guide_preset_image' => 'http://app.owin.kr/data2/owin/guide_image_preset.png',
                    'no_order' => $order->no_order,
                    'use_benefit_coupon_yn' => $order->memberBenefitCoupon?->use_coupon_yn ?? 'A' ,
                    'use_benefit_coupon_no' => $order->memberBenefitCoupon?->no_benefit ?? '',
                ];
            })->first();
    }

    public function getOrderList(int $noUser, int $currentPage, int $size)
    {

        $orders = OrderList::where('no_user', $noUser)
            ->whereIn('cd_payment_status', ['603100', '603200', '603300', '603900'])
            ->with(['shop', 'partner', 'shop.shopOil', 'orderPayment'])->get()->filter(function ($query) {
                return $query->partner->cd_biz_kind == '201300';
            })->sortByDesc('dt_reg')->map(function ($collect) {
                $bizKind = $collect->partner->cd_biz_kind;
                $pickupStatus = $collect->cd_pickup_status == '602390' ? '602100' : $collect->cd_pickup_status;
                $orderStatus = getOrderStatus(
                    $bizKind,
                    $collect->cd_order_status,
                    $pickupStatus,
                    $collect->cd_payment_status,
                    $collect->cd_alarm_event_type,
                    $collect->orderPayment?->cd_pg_result
                );
                return [
                    'no_order' => $collect->no_order,
                    'nm_order' => $collect->nm_order,
                    'nm_shop' => $collect->shop?->nm_shop,
                    'nm_partner' => $collect->partner?->nm_partner,
                    'cd_biz_kind' => $bizKind,
                    'at_price' => $collect->at_price,
                    'at_liter_gas' => $collect->at_liter_gas,
                    'yn_gas_order_liter' => $collect->yn_gas_order_liter,
                    'at_price_real_gas' => $collect->at_price_real_gas,
                    'ds_bi' => $collect->partner->ds_pin ? Common::getImagePath($collect->partner->ds_bi) : Common::getImagePath("/data2/partner/default.png"),
                    'ds_pin' => $collect->partner->ds_pin ? Common::getImagePath($collect->partner->ds_pin) : Common::getImagePath("/data2/partner/default.png"),
                    'cd_payment_status' => $collect->cd_payment_status,
                    'cd_pickup_status' => $pickupStatus,
                    'cd_order_status' => $collect->cd_order_status,
                    'cd_pg_result' => $collect->orderPayment?->cd_pg_result,
                    'ds_pickup_time' => $bizKind == '201300' ? $collect->cd_pickup_status < 602400 ?
                        Carbon::createFromFormat('Y-m-d H:i:s', $collect->dt_reg)->format('Y-m-d H:i:s')
                        : Carbon::createFromFormat('Y-m-d H:i:s', $collect->dt_pickup_status)->format('Y-m-d H:i:s') : null,
                    'cd_alarm_event_type' => $collect->cd_alarm_event_type,
                    'ds_uni' => $collect->shop?->shopOil?->ds_uni,
                    'ds_booking_time' => '',
                    'cd_rsv_status' => '',
                    'dt_order' => Carbon::createFromFormat('Y-m-d H:i:s', $collect->dt_reg)->format('YmdHis'),
                    'cd_status' => $orderStatus ? $orderStatus[0] : '',
                    'nm_status' => $orderStatus ? $orderStatus[1] : '',
                    'at_lat' => $collect->shop?->at_lat,
                    'at_lng' => $collect->shop?->at_lng,
                    'ds_address' => $collect->shop?->ds_address . $collect->shop?->ds_address2,
                    'ds_new_adr' => $collect->shop?->shopOil?->ds_new_adr,
                ];
            });
        return [
            'count' => count($orders),
            'rows' => $orders->forPage($currentPage, $size)->values(),
        ];
    }


    public function getMemberInfo(int $noUser)
    {

        return MemberService::getMember([
            'no_user' => $noUser
        ])->whenEmpty(function () {
            throw new TMapException('M1138', 400);
        })->map(function ($collect) {
            return [
                'result' => '1',
                'no_user' => $collect->no_user,
                'ds_phone' => $collect->ds_phone,
                'id_user' => $collect->id_user,
                'nm_nick' => $collect->nm_nick,
                'cd_reg_kind' => $collect->cd_reg_kind,
                'cd_auth_type' => $collect->cd_auth_type,
                'yn_push_msg' => $collect->yn_push_msg,
                'yn_push_msg_event' => $collect->yn_push_msg_event,
                'at_cash' => $collect->at_cash,
                'at_event_cash' => $collect->at_event_cash,
                'ds_profile_path' => $collect->memberDetail?->ds_profile_path,
                'yn_account_status_rsm' => $collect->memberDetail->yn_account_status_rsm,
            ];
        })->first();
    }

    public function getOilShopList($request)
    {
        $shop = Shop::select([
            'shop.no_shop',
            'shop_oil.ds_uni',
            'partner.nm_partner',
            'shop.nm_shop',
            'shop.ds_tel',
            'shop.ds_status',
            'shop_oil.yn_self',
            'shop_oil.ds_new_adr',
            'shop_oil.ds_van_adr',
            'shop.at_lat',
            'shop.at_lng',
        ])->join('partner', 'shop.no_partner', '=', 'partner.no_partner')
            ->join('shop_oil', 'shop.no_shop', '=', 'shop_oil.no_shop')
            ->where('partner.cd_biz_kind', '=', '201300');

        if (empty(data_get($request, 'type')) == false && empty(data_get($request, 'code')) == false) {
            if ($request['type'] == 'OWIN') {
                $shop = $shop->where('shop.no_shop', $request['code']);
            } else {
                $shop = $shop->where('shop_oil.ds_uni', '=', $request['code']);
            }
        }

        $shop = $shop->with([
            'shopDetail',
            'shopOilPrice',
            'shopOptTime',
        ])->get()->map(function ($shop) {
            return [
                'no_shop' => $shop->no_shop,
                'ds_uni' => $shop->ds_uni,
                'nm_partner' => $shop->nm_partner,
                'nm_shop' => $shop->nm_shop,
                'nm_pause_type' => match (empty($shop->shopDetail->cd_pause_type)) {
                    true => "",
                    default => CodeService::getCode($shop->shopDetail->cd_pause_type)->nm_code
                },
                'ds_tel' => $shop->ds_tel,
                'ds_biz_num' => $shop->shopDetail?->ds_biz_num,
                'ds_status' => $shop->ds_status,
                'list_oil_info' => $shop->shopOilPrice->map(function ($price) {
                    return [
                        'cd_gas_kind' => $price->cd_gas_kind,
                        'nm_gas_kind' => Code::conf("gas_kind_product_name.{$price->cd_gas_kind}"),
                        'at_price' => $price->at_price,
                        'at_oil_price1' => 30000,
                        'at_oil_litre1' => Oil::getLiterCalculate(30000, $price->at_price),
                        'at_oil_price2' => 50000,
                        'at_oil_litre2' => Oil::getLiterCalculate(50000, $price->at_price),
                        'at_oil_price3' => 70000,
                        'at_oil_litre3' => Oil::getLiterCalculate(70000, $price->at_price),
                        'at_oil_litre4' => 20,
                        'at_oil_price4' => Oil::getOilPriceCalculate(20, $price->at_price),
                        'at_oil_litre5' => 30,
                        'at_oil_price5' => Oil::getOilPriceCalculate(30, $price->at_price),
                        'at_oil_litre6' => 40,
                        'at_oil_price6' => Oil::getOilPriceCalculate(40, $price->at_price),
                        'at_oil_price7' => 149900,
                        'at_oil_litre7' => Oil::getLiterCalculate(149900, $price->at_price),
                    ];
                }),
                'shop_opt_time' => $shop->shopOptTime->map(function ($optTime) {

                    return [
                        'nm_weekday' => Common::getWeekDay($optTime->nt_weekday),
                        'ds_open_time' => preg_replace('/^(\d{2})(\d{2})$/', '\1:\2', $optTime->ds_open_time),
                        'ds_close_time' => preg_replace('/^(\d{2})(\d{2})$/', '\1:\2', $optTime->ds_close_time),
                    ];
                }),
                'yn_self' => $shop->yn_self,
                'ds_new_adr' => $shop->ds_new_adr,
                'ds_van_adr' => $shop->ds_van_adr,
                'at_lat' => $shop->at_lat,
                'at_lng' => $shop->at_lng,
                'yn_open' => $shop->shopHolidayExists ? 'N' : 'Y',
            ];
        });

        if (empty(data_get($request, 'page_now')) == false) {
            $shop = $shop->forPage($request['page_now'], 100);
        }

        return $shop;
    }
}
