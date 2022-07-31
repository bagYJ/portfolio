<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Jobs\ProcessArkServer;
use App\Models\MemberRetailCouponUsepartner;
use App\Queues\Socket\ArkServer;
use App\Services\CardService;
use App\Services\CodeService;
use App\Services\CouponService;
use App\Services\MemberService;
use App\Services\PromotionService;
use App\Utils\Code;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Promotion extends Controller
{
    /**
     * 오윈 쿠폰등록 (/promotion/owin_coupon_regist)
     *
     * @param Request $request
     * @return JsonResponse
     * @throws OwinException
     */
    public function couponRegist(Request $request): JsonResponse
    {
        $request->validate([
            'no_event' => 'required'
        ]);
        $noPin = substr(preg_replace(['/\s+/', '/\r\n|\r|\n/'], ['', ''], $request->post('no_event')), 0, 200);
        $memberService = new MemberService();
        $couponService = new CouponService();
        $promotionService = new PromotionService();

        if (empty(Auth::user()->ds_ci) === true || Auth::user()->ds_status == EnumYN::N->name) {
            throw new OwinException(Code::message('M1501'));
        }
        if (str_starts_with($request->post('no_event'), 'owin') && strlen($request->post('no_event')) == 9) {
            throw new OwinException(Code::message('P2302'));
        }

        try {
            DB::beginTransaction();
            $cardList = (new CardService())->cardList(Auth::id());
            if (array_intersect(array_keys(Code::conf('unable_card')), $cardList->pluck('cd_card_corp')->all())) {
                throw new OwinException(Code::message('P1029'));
            }

            $pinInfo = $promotionService->pinInfo($noPin)->whenEmpty(function () {
                throw new OwinException(Code::message('P2360'));
            })->first();

            if ($pinInfo->no_deal != env('NO_DEAL_PRESS')) {
                MemberService::getMember([
                    'ds_ci' => Auth::user()->ds_ci
                ])->whenNotEmpty(function ($ciMemberList) use ($pinInfo) {
                    $noUsers = $ciMemberList->pluck('no_user')->all();
                    $noOverlapSeq = PromotionService::promotionOverlap([
                        'no_basis_seq' => $pinInfo->no_deal,
                        'ds_status' => EnumYN::Y->name,
                    ]);

                    PromotionService::memberDealbyNoDeal(
                        $noUsers,
                        $pinInfo->no_deal,
                        $noOverlapSeq->where('ds_type', 'P')->all()
                    )->whenNotEmpty(function () {
                        throw new OwinException(Code::message('P2370'));
                    });

                    MemberService::memberEvent(
                        $noUsers,
                        $noOverlapSeq->where('ds_type', 'E')->all(),
                        env('FNB_EVENT_SEQ')
                    )->whenNotEmpty(function () {
                        throw new OwinException(Code::message('P2371'));
                    });
                });
            }

            $couponService->todayMemberOwinCouponRequest([
                'no_user' => Auth::id(),
                'yn_success' => EnumYN::N->name
            ])->whenNotEmpty(function ($collect) {
                if ($collect->count() > 10) {
                    throw new OwinException(Code::message('P2380'));
                }
            });

            $dealInfo = $promotionService->promotionDealFirst([
                'no_deal' => $pinInfo->no_deal
            ]);

            MemberService::memberDeal([
                'no_user' => Auth::id(),
                'no_deal' => $pinInfo->no_deal
            ])->whenNotEmpty(function () {
                throw new OwinException(Code::message('P2370'));
            }, function () use ($noPin, $pinInfo) {
                MemberService::memberDeal([
                    'no_pin' => $noPin,
                    'no_deal' => $pinInfo->no_deal
                ])->whenNotEmpty(function () {
                    throw new OwinException(Code::message('P2340'));
                });
            });

            if (
                strlen($noPin) >= 8
                && empty($dealInfo->ds_index_char) === false
                && !(
                    strcasecmp($dealInfo->dt_deal_use_end->format('Y-m-d H:i:s'), substr($noPin, 0, 2))
                    && $dealInfo->cd_deal_type == 129100
                )
            ) {
                throw new OwinException(Code::message('P2300'));
            }
            if ($dealInfo->dt_deal_use_end < date('Y-m-d') || $dealInfo->dt_deal_use_st > date('Y-m-d')) {
                throw new OwinException(Code::message('P2300'));
            }

            if ($pinInfo->no_deal != env('NO_DEAL_PRESS') && $dealInfo->yn_single_pin != EnumYN::Y->name) {
                $message = match ($pinInfo->cd_deal_status) {
                    '128100' => null,
                    '128900' => 'P2303',
                    default => match ($pinInfo->no_user) {
                        Auth::id() => match ($pinInfo->cd_deal_status) {
                            '128300' => 'P2326',
                            default => 'P2327',
                        },
                        default => 'P2301'
                    }
                };

                if (is_null($message) === false) {
                    throw new OwinException($message);
                }
            }

            $memberService->memberDealFirstOrCreate([
                'no_pin' => $noPin,
                'no_deal' => $pinInfo->no_deal,
                'no_user' => Auth::id()
            ], [
                'yn_pointcard_issue' => EnumYN::N->name,
                'dt_deal_use_end' => $dealInfo->dt_deal_use_end
            ]);

            if ($dealInfo->cd_biz_kind != '201800') {
                $upperCouponInfo = $couponService->gsCouponEvent([
                    'no_part_cpn_event' => $dealInfo->no_part_cpn_event
                ])->first();
                $partnerCouponCnt = match (empty($upperCouponInfo->no_event)) {
                    false => $couponService->memberPartnerCoupon([
                        'no_user' => Auth::id(),
                        'no_event' => $upperCouponInfo->no_event
                    ])->count(),
                    default => 0
                };
                if ((empty(Auth::user()->ds_ci) === false && $partnerCouponCnt < 1) === false) {
                    throw new OwinException(Code::message('P2329'));
                }

                while (true) {
                    $dsCpnNo = substr((string)time(), 0, 3) . substr(microtime(), 2, 5) . sprintf('%03d', mt_rand(0, 999));
                    if ($couponService->memberPartnerCoupon([
                            'ds_cpn_no_internal' => $dsCpnNo
                        ])->count() <= 0) {
                        break;
                    }
                }

                CouponService::memberPartnerCouponRegist([
                    'ds_cpn_no_internal' => $dsCpnNo,
                    'ds_cpn_no' => $dsCpnNo,
                    'no_user' => Auth::id(),
                    'no_partner' => env('GS_NO_PARTNER'),
                    'use_coupon_yn' => EnumYN::Y->name,
                    'ds_cpn_nm' => $upperCouponInfo->ds_cpn_title,
                    'use_disc_type' => '00',
                    'at_disct_money' => $dealInfo->at_disct_price,
                    'at_limit_money' => 0,
                    'cd_cpe_status' => '121100',
                    'cd_mcp_status' => '122100',
                    'no_event' => $dealInfo->no_part_cpn_event,
                    'dt_use_start' => now()->startOfDay(),
                    'dt_use_end' => now()->addDays($upperCouponInfo->at_expire_day - 1)->endOfDay(),
                    'dt_start_from_made' => now()->startOfDay(),
                    'dt_end_from_made' => now()->addDays($upperCouponInfo->at_expire_day - 1)->endOfDay(),
                    'id_admin' => Auth::user()->nm_user,
                    'yn_is_reused' => EnumYN::N->name,
                    'yn_real_pubs' => EnumYN::N->name,
                ]);

                $promotionService->promotionPinUpdate([
                    'no_user' => Auth::id(),
                    'cd_deal_status' => '128200',
                    'ds_cpn_no' => $dsCpnNo
                ], [
                    'no_pin',
                    $noPin
                ]);
            } else {
                while (true) {
                    $noCoupon = substr((string)time(), 0, 3) . substr(microtime(), 2, 5) . sprintf('%03d', mt_rand(0, 999));
                    if ($couponService->memberRetailCoupon([
                            'no_coupon' => $noCoupon
                        ])->count() <= 0) {
                        break;
                    }
                }

                $retailCouponInfo = CouponService::retailCouponEvent([
                    'no' => $dealInfo->retail_no_event
                ])->whenNotEmpty(function ($retailCouponInfo) use ($noCoupon) {
                    $retailCouponInfo->first()->retailCouponEventUsepartner->map(function ($usePartner) use ($noCoupon
                    ) {
                        (new MemberRetailCouponUsepartner([
                            'no_user' => Auth::id(),
                            'no_coupon' => $noCoupon,
                            'cd_cpn_condi_type' => '125100',
                            'ds_target' => $usePartner->no_partner
                        ]))->saveOrFail();
                    });
                })->first();

                $couponService->memberRetailCouponRegist([
                    'no_user' => Auth::id(),
                    'no_coupon' => $noCoupon,
                    'no_event' => $dealInfo->retail_no_event,
                    'nm_event' => $retailCouponInfo->nm_event,
                    'use_coupon_yn' => EnumYN::Y->name,
                    'cd_mcp_status' => '122100',
                    'at_disct_money' => $retailCouponInfo->at_disct_money,
                    'at_expire_day' => $retailCouponInfo->at_expire_day,
                    'dt_use_start' => date('Y-m-d 00:00:00'),
                    'dt_use_end' => date(
                        'Y-m-d 23:59:59',
                        strtotime(sprintf('+%d days', ($retailCouponInfo->at_expire_day - 1)))
                    ),
                    'at_min_price' => $retailCouponInfo->at_min_price,
                    'cd_issue_kind' => '131300',
                    'cd_calculate_main' => $retailCouponInfo->cd_calculate_main,
                    'user_type' => 'C',
                ]);

                $promotionService->promotionPinUpdate([
                    'no_pin' => $noPin
                ], [
                    'no_user' => Auth::id(),
                    'cd_deal_status' => '128200',
                    'ds_cpn_no' => $noCoupon
                ]);

                $couponService->memberRetailCouponRequestRegist([
                    'no_user' => Auth::id(),
                    'no_coupon' => $noCoupon,
                    'no_event' => $dealInfo->retail_no_event,
                    'nm_event' => $retailCouponInfo->nm_event,
                    'use_coupon_yn' => EnumYN::Y->name,
                    'cd_mcp_status' => '122100',
                    'at_disct_money' => $retailCouponInfo->at_disct_money,
                    'at_expire_day' => $retailCouponInfo->at_expire_day,
                    'dt_use_start' => date('Y-m-d 00:00:00'),
                    'dt_use_end' => date(
                        'Y-m-d 23:59:59',
                        strtotime(sprintf('+%d days', ($retailCouponInfo->at_expire_day - 1)))
                    ),
                    'at_min_price' => $retailCouponInfo->at_min_price,
                    'cd_issue_kind' => '131300',
                    'cd_calculate_main' => $retailCouponInfo->cd_calculate_main,
                    'user_type' => 'C',
                    'list_usepartner' => $retailCouponInfo->retailCouponEventUsepartner->pluck('no_partner')->implode(
                        ','
                    ),
                    'yn_success' => EnumYN::Y->name,
                ]);
            }

            if ($pinInfo->no_deal == env('NO_DEAL_PRESS')) {
                $memberService->memberGroupFirstOrCreate([
                    'ds_phone' => Auth::user()->ds_phone,
                    'id_user' => Auth::user()->id_user,
                    'cd_mem_group' => '112100'
                ], [
                    'no_user' => Auth::id()
                ]);
            }

            $memberService->owinCouponRequest([
                'no_pin' => $noPin,
                'no_user' => Auth::id(),
                'no_deal' => $pinInfo->no_deal,
                'yn_success' => EnumYN::Y->name,
                'reg_msg' => Code::message('P2399'),
                'reg_code' => Code::message('P2399')
            ]);
            DB::commit();

            return response()->json([
                'result' => true
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('[P2328] couponRegist error', [$e->getMessage()]);
            throw new OwinException(Code::message('P2328'));
        }
    }

    public function gsCouponRegist(Request $request): JsonResponse
    {
        $request->validate([
            'no_event' => 'required'
        ]);

        try {
            DB::beginTransaction();
            CouponService::memberPartnerCoupon([
                'ds_cpn_no' => $request->no_event
            ])->whenNotEmpty(function () {
                throw new OwinException(Code::message('P2340'));
            });

            CouponService::todayCouponRequest([
                'no_user' => Auth::id(),
                'yn_success' => 'N'
            ])->whenNotEmpty(function ($collect) {
                if ($collect->count() > 2) {
                    throw new OwinException(Code::message('P2380'));
                }
            });

            CouponService::memberPartnerCouponRegist([
                'ds_cpn_no_internal' => $request->post('no_event'),
                'ds_cpn_no' => $request->post('no_event'),
                'no_user' => Auth::id(),
                'no_partner' => env('GS_NO_PARTNER'),
                'use_coupon_yn' => EnumYN::N->name,
                'use_disc_type' => '00',
                'at_disct_money' => 0,
                'at_limit_money' => 0,
                'cd_cpe_status' => '121200',
                'cd_mcp_status' => '122100',
                'yn_real_pubs' => EnumYN::Y->name,
            ]);

            CouponService::memberCouponRequestRegist([
                'no_user' => Auth::id(),
                'no_partner' => env('GS_NO_PARTNER'),
                'ds_cpn_no' => $request->post('no_event'),
                'yn_success' => EnumYN::Y->name,
            ]);

            ProcessArkServer::dispatch(
                new ArkServer(
                    type: 'SOCKET',
                    method: 'oilCoupon',
                    body: $request->no_event,
                    header: 'KS'
                )
            )->onConnection('database');
            DB::commit();

            return response()->json([
                'result' => true
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            Log::channel('error')->error('[P2328] gsCouponRegist error', [$e->getMessage()]);
            throw new OwinException(Code::message('P2328'));
        }
    }

    public function gsCouponDetail(string $noEvent): JsonResponse
    {
        CouponService::memberPartnerCoupon([
            'ds_cpn_no' => $noEvent,
            'no_user' => Auth::id()
        ])->whenEmpty(function () {
            throw new OwinException(Code::message('P2360'));
        }, function ($coupon) {
            if ($coupon->first()->use_coupon_yn == 'N') {
                CouponService::removeMemberPartnerCoupon($coupon->first());

                $cardList = Code::conf('gs_card_list');
                $message = match (empty(data_get($cardList, $coupon->first()->cd_payment_card))) {
                    false => sprintf(Code::message('C0001'), data_get($cardList, $coupon->first()->cd_payment_card)),
                    default => match ($coupon->first()->ds_result_code) {
                        '99998' => Code::message('C0003'),
                        default => Code::message('C0002')
                    }
                };

                throw new OwinException($message);
            }
        });

        return response()->json([
            'result' => true
        ]);
    }

    public function pointCardRegist(Request $request): JsonResponse
    {
        $request->validate([
            'agree_result' => 'required|array',
            'agree_result.0' => 'required|in:' . EnumYN::Y->name,
            'agree_result.1' => 'required|in:' . EnumYN::Y->name,
            'agree_result.2' => 'required|in:' . EnumYN::Y->name,
            'agree_result.4' => 'required|in:' . EnumYN::Y->name,
        ]);
        $cardService = new CardService();
        $memberService = new MemberService();

        $pointCardInfo = $cardService->memberPointCard([
            'no_user' => Auth::id(),
            'cd_point_cp' => env('GS_CD_POINT_SALE_CP')
        ])->whenNotEmpty(function ($collection) {
            if ($collection->first()->yn_delete == EnumYN::N->name) {
                throw new OwinException(Code::message('SC1110'));
            }
        })->first();

        $memberDeal = $memberService->memberDeal([
            'no_user' => Auth::id()
        ])->first();
        $promotionBandwidthList = (new PromotionService())->promotionDeal(null, ['ds_gs_sale_code']);

//        beacon.ds_sn 조건 제거 (beacon 삭제)
        $promotionInfo = match (Auth::user()->cd_mem_level) {
            '104600' => $promotionBandwidthList->firstWhere('no_deal', '1007'),
            default => match ($memberDeal?->no_deal) {
                '1000' => $promotionBandwidthList->firstWhere('no_deal', '1000'),
                default => $promotionBandwidthList->firstWhere('no_deal', '1004')
            }
        };

        $maxPointcard = $cardService->gsSaleCard([
            'no_user' => Auth::id()
        ], $promotionInfo->ds_bandwidth_st, $promotionInfo->ds_bandwidth_end)->max('id_pointcard');

        $cardNumber = '';
        if (empty($maxPointcard) === false) {
            $cardNumber = $maxPointcard;
        } else {
            $maxNextPointcard = $cardService->gsSaleCard([], $promotionInfo->ds_bandwidth_st, $promotionInfo->ds_bandwidth_end)->max('id_pointcard');

            $cardNumber = match (empty($maxNextPointcard)) {
                true => $promotionInfo->ds_bandwidth_st,
                false => substr($maxNextPointcard, 0, 10) . ((int)substr($maxNextPointcard, -6) + 1)
            };
            $cardNumber = match (in_array($cardNumber, ['0190612000509279', '0190612000571756'])) {
                true => substr($cardNumber, 0, 10) . ((int)substr($cardNumber, -6) + 1),
                default => $cardNumber
            };

            if ($cardNumber > $promotionInfo->ds_bandwidth_end) {
                throw new OwinException(Code::message('P2203'));
            }
        }

        $response = (new ArkServer(
            type: 'ARK',
            method: 'card',
            body: ArkServer::makeMemberPacketSale('member', Auth::user(), $promotionInfo->ds_gs_sale_code, $cardNumber, $request->post('agree_result'))
        ))->init();

        if ($response['result_code'] != '00000') {
            throw new OwinException(Code::message('SC1091'));
        }

        CardService::upsertGsSalesCard([
            'id_pointcard' => $response['no_card'],
            'no_user' => Auth::id()
        ], [
            'ds_validity' => $response['validity'],
            'ds_card_name' => $response['nm_card']
        ]);

        $pointCard = $cardService->gsSaleCard([
            'no_user' => Auth::id(),
            'id_pointcard' => $response['no_card']
        ])->first();

        $pointResponse = (new ArkServer(
            type: 'ARK',
            method: 'oil',
            body: ArkServer::makeCardInfoPacketSale('card_info', Auth::user(), $response['no_card'])
        ))->init();

        $cardInfo = match ($pointResponse['result_code']) {
            '00000' => [
                    'ds_sale_start' => $pointCard->ds_sale_start ?? getenv('OWIN_GS_SALE_START'),
                    'ds_sale_end' => $pointCard->ds_sale_end ?? getenv('OWIN_GS_SALE_END'),
                    'at_limit_one_use' => getenv('OWIN_GS_SALE_PRICE_ONE'),
                    'at_limit_price' => getenv('OWIN_GS_SALE_PRICE_MONTH'),
                    'at_limit_total_use' => getenv('OWIN_GS_SALE_PRICE_TOTAL'),
                    'yn_used' => 'Y'
                ] + $pointResponse,
            default => [
                'ds_validity' => null,
                'ds_card_name' => null
            ]
        };
        CardService::updateGsSalesCard($pointCard, $cardInfo);

        $memberPointCardParameter = match (empty($pointCardInfo)) {
                false => [],
                default => [
                    'yn_sale_card' => 'Y',
                    'cd_point_cp' => getenv('GS_CD_POINT_SALE_CP'),
                    'yn_agree01' => data_get($request->agree_result, 0, 'N'),
                    'yn_agree02' => data_get($request->agree_result, 1, 'N'),
                    'yn_agree03' => data_get($request->agree_result, 2, 'N'),
                    'yn_agree04' => data_get($request->agree_result, 3, 'N'),
                    'yn_agree05' => data_get($request->agree_result, 4, 'N'),
                    'yn_agree06' => data_get($request->agree_result, 5, 'N'),
                    'yn_agree07' => data_get($request->agree_result, 6, 'N')
                ]
            } + [
                'yn_delete' => 'N',
                'id_pointcard' => $response['no_card']
            ];
        CardService::upsertMemberPointcard([
            'no_user' => Auth::id()
        ], $memberPointCardParameter);

        return response()->json([
            'result' => true
        ]);
    }

    public function cardPoint(string $idPointcard): JsonResponse
    {
        $response = (new ArkServer(
            type: 'ARK',
            method: 'point',
            body: ArkServer::makePointPacket('point', Auth::user(), $idPointcard)
        ))->init();

        if ($response['result_code'] != '00000') {
            throw new OwinException(Code::message('SC1100'));
        }

        return response()->json([
            'result' => true,
            'point' => (int)$response['point']
        ]);
    }

    public function removePointCard(string $idPointcard): JsonResponse
    {
        Auth::user()->memberPointCard->where('id_pointcard', $idPointcard)->whenNotEmpty(function ($card) use (
            $idPointcard
        ) {
            if ($card->first()->yn_sale_card == 'Y') {
                CardService::upsertMemberPointcard([
                    'no_user' => Auth::id(),
                    'id_pointcard' => $idPointcard
                ], [
                    'yn_delete' => 'Y'
                ]);
            } else {
                $card->first()->delete();
            }
        }, function () {
            throw new OwinException(Code::message('SC9999'));
        });

        return response()->json([
            'result' => true
        ]);
    }

    public function pointCardList(): JsonResponse
    {
        $pointType = CodeService::getGroupCode('124');

        return response()->json([
            'result' => true,
            'card_list' => Auth::user()->memberPointCard->map(function ($card) use ($pointType) {
                return [
                    'id_pointcard' => $card->id_pointcard,
                    'cd_point_cp' => $card->cd_point_cp,
                    'point_cp' => $pointType->firstWhere('no_code', $card->cd_point_cp)->nm_code
                ];
            })
        ]);
    }
}
