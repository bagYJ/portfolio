<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\EnumYN;
use App\Enums\SearchBizKind;
use App\Services\CouponService;
use App\Utils\Code;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Coupon extends Controller
{
    public function lists(Request $request): JsonResponse
    {
        $request->validate([
            'use_coupon_yn' => Rule::in(EnumYN::keys()),
            'bizKind' => Rule::in(SearchBizKind::keys()),
        ]);

        $couponService = new CouponService();
        $coupon = match ($request->bizKind) {
            SearchBizKind::FNB->name => $couponService->myFnbCoupon(
                Auth::id(),
                $request->use_coupon_yn,
                getAppType()->value
            ),
            SearchBizKind::OIL->name => $couponService->myOilCoupons(
                Auth::id(),
                $request->use_coupon_yn,
                getAppType()->value
            ),
            SearchBizKind::RETAIL->name => $couponService->myRetailCoupon(
                Auth::id(),
                $request->use_coupon_yn,
                getAppType()->value
            ),
            SearchBizKind::WASH->name => $couponService->myWashCoupon(
                Auth::id(),
                $request->use_coupon_yn,
            ),
            SearchBizKind::PARKING->name => $couponService->myParkingCoupon(
                Auth::id(),
                $request->use_coupon_yn,
                getAppType()->value,
            ),
            default => $couponService->myCoupon(Auth::id(), $request->use_coupon_yn, getAppType()->value)
        };

        return response()->json([
            'result' => true,
            'coupon_list' => $coupon->map(function ($coupon) {
                return [
                    'no' => $coupon->no_event,
                    'coupon_type' => $coupon->coupon_type,
                    'coupon_type_nm' => Code::conf(sprintf('coupon_type.%s', $coupon->coupon_type)),
                    'coupon_nm' => $coupon->nm_event,
                    'available_partner' => $coupon?->available_partner,
                    'available_shop' => $coupon?->available_shop,
                    'available_card' => $coupon?->available_card,
                    'available_weekday' => $coupon?->available_weekday,
                    'available_category' => $coupon?->available_category,
                    'available_product' => $coupon?->available_product,
                    'at_discount' => $coupon->at_discount,
                    'discount_type' => $coupon?->discount_type,
                    'at_price_limit' => $coupon?->at_price_limit,
                    'at_max_disc' => $coupon?->at_max_disc,
                    'expire_date' => match ($coupon->dt_expire) {
                        '2999-12-31 23:59:59' => null,
                        default => Carbon::createFromFormat('Y-m-d H:i:s', $coupon->dt_expire)->format('Y-m-d')
                    }
                ];
            })
        ]);
    }

    public function detail(Request $request): JsonResponse
    {
        $request->validate([
            'biz_kind' => ['required', Rule::in(SearchBizKind::keys())],
            'no' => 'required|integer'
        ]);

        $response = $this->lists($request);

        return match ($response->status()) {
            200 => response()->json([
                'result' => true,
                ...$response->getOriginalContent()['coupon_list']->where('no', $request->no)->first()
            ]),
            default => $response
        };
    }
}
