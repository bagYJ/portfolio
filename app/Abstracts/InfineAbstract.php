<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\GasType;
use App\Enums\Method;
use App\Models\MemberShopEnterLog;
use App\Models\OrderList;
use App\Models\OrderOilEventLog;
use App\Models\OrderPayment;
use App\Models\Shop;
use App\Models\ShopOil;
use App\Models\ShopOilPrice;
use App\Models\ShopOilUnit;
use App\Requests\Infine\Approval;
use App\Requests\Infine\ApprovalResult;
use App\Requests\Infine\Cancel;
use App\Requests\Page;
use App\Responses\Infine\Init;
use App\Responses\Infine\Lists;
use App\Responses\Infine\ListsDataShops;
use App\Responses\Infine\ListsDataShopsArks;
use App\Responses\Infine\ListsDataShopsPrices;
use App\Utils\Common;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class InfineAbstract
{
    /**
     * @param Method $method
     * @param string $path
     * @param array|null $options
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    private static function client(Method $method, string $path, ?array $options = []): array
    {
        try {
            $response = Common::client($method, sprintf('%s%s', getenv('INFINE_URI'), $path), $options);
            $content = json_decode($response->getBody()->getContents(), true);
            Log::channel('client')->info(sprintf('%s %s RESPONSE ', sprintf('%s%s', getenv('INFINE_URI'), $path), $method->name), $content);

            return match ($response->getStatusCode()) {
                200 => $content,
                400 => throw new BadRequestHttpException(message: data_get($content, 'message'), code: data_get($content, 'code')),
                401 => throw new AuthorizationException(message: data_get($content, 'message'), code: data_get($content, 'code')),
                default => throw new Exception(data_get($content, 'message'), data_get($content, 'code'))
            };
        } catch (BadRequestHttpException $e) {
            throw new BadRequestHttpException(message: $e->getMessage(), code: $e->getCode());
        } catch (AuthorizationException $e) {
            throw new AuthorizationException(message: $e->getMessage(), code: $e->getCode());
        } catch (Throwable $t) {
            throw new Exception($t->getMessage(), $t->getCode());
        }
    }

    /**
     * @param Page $request
     * @return void
     * @throws AuthorizationException
     * @throws Exception
     */
    public static function list(Page $request): void
    {
        $response = new Lists(self::client(Method::GET, getenv('INFINE_PATH_LIST'), ['json' => $request]));

        match ($response->code == env('RETURN_TRUE')) {
            true => (function () use ($response) {
                $shopOils = (new ShopOil())->whereIn('ds_uni', $response->data->shops->pluck('ds_uni')->all())->get();
                $noShop = (new Shop())->where('no_partner', env('INFINE_NO_PARTNER'))->max('no_shop');

                $response->data->shops->map(function (ListsDataShops $shop) use ($shopOils, $noShop) {
                    try {
                        DB::beginTransaction();
                        $maxNoShop = match ($shopOils->where('ds_uni', $shop->ds_uni)->count()) {
                            0 => ++$noShop,
                            default => $shopOils->where('ds_uni', $shop->ds_uni)->first()->no_shop
                        };
                        (new Shop())->updateOrCreate([
                            'no_shop' => $maxNoShop,
                            'no_partner' => env('INFINE_NO_PARTNER')
                        ], [
                            'nm_shop' => $shop->nm_shop,
                            'ds_tel' => $shop->ds_tel,
                            'ds_open_time' => $shop->ds_open_time,
                            'ds_close_time' => $shop->ds_close_time,
                            'at_lat' => $shop->at_lat,
                            'at_lng' => $shop->at_lng
                        ]);
                        (new ShopOil())->updateOrCreate([
                            'no_shop' => $maxNoShop,
                            'ds_uni' => $shop->ds_uni
                        ], [
                            'ds_poll_div' => $shop->ds_poll_div,
                            'ds_van_adr' => $shop->ds_van_adr,
                            'ds_new_adr' => $shop->ds_new_adr,
                            'ds_tel' => $shop->ds_tel,
                            'yn_maint' => $shop->yn_maint,
                            'yn_cvs' => $shop->yn_cvs,
                            'yn_car_wash' => $shop->yn_car_wash,
                            'yn_self' => $shop->yn_self
                        ]);
                        $shop->arks->map(function (ListsDataShopsArks $arks) use ($maxNoShop) {
                            (new ShopOilUnit())->updateOrCreate([
                                'no_shop' => $maxNoShop,
                                'ds_unit_id' => $arks->unit_id
                            ], [
                                'ds_display_ark_id' => $arks->unit_id
                            ]);
                        });
                        $shop->prices->map(function (ListsDataShopsPrices $price) use ($shop, $maxNoShop) {
                            (new ShopOilPrice())->updateOrCreate([
                                'no_shop' => $maxNoShop,
                                'cd_gas_kind' => $price->cd_gas_kind,
                                'ds_uni' => $shop->ds_uni
                            ], [
                                'ds_prod' => GasType::gasKind($price->cd_gas_kind),
                                'at_price' => $price->at_price,
                                'dt_trade' => $price->dt_trade,
                                'tm_trade' => $price->tm_trade
                            ]);
                        });
                        DB::commit();
                    } catch (Throwable $t) {
                        DB::rollBack();
                        Log::channel('error')->critical($t->getMessage(), [$t->getFile(), $t->getLine(), $t->getTraceAsString()]);

                    }
                });
            })(),
            default => throw new Exception($response->message, $response->code)
        };
    }

    /**
     * @param string $noOrder
     * @return void
     * @throws AuthorizationException
     * @throws Exception
     */
    public static function init(string $noOrder): void
    {
        $order = (new OrderList())->findOrFail($noOrder);
        $response = new Init(self::client(Method::GET, getenv('INFINE_PATH_INIT'), ['json' => (new \App\Requests\Infine\Init($order))]));

        match ($response->code == env('RETURN_TRUE') && $response->data->result_code == env('RETURN_TRUE')) {
            true => (function () use ($order, $response) {
//                $order->setAttribute('ds_unit_id', $response->data->no_nozzle);
                (new OrderOilEventLog([
                    'no_order' => $order->no_order,
                    'ds_unit_id' => $response->data->no_nozzle,
                    'cd_order_event_type' => 614300,
                    'ds_credit_card_no' => $response->data->no_card,
                    'ds_credit_auth_no' => $response->data->no_approval_temp,
                    'ds_trdate_credit' => $response->data->dt_approval_temp,
                    'ds_bon_card_no' => $response->data->no_bonuscard,
                    'ds_bon_card_auth_no' => $response->data->no_bonuscard_approve,
                    'ds_trdate_bon' => $response->data->no_bonuscard_approve ? now()->format('YmdHis') : null,
                    'ds_car_number' => $order->ds_car_number,
                    'dt_reg' => now()->format('Y-m-d H:i:s'),
                    'ds_payment_type' => '0032',
                    'yn_payment_result' => 'Y',
                    'yn_last_check' => 'N',
                    'at_discount_pg' => $response->data->at_coupon + $response->data->at_bonuscard,
                    'at_price_pg' => $response->data->at_price,
                    'oil_liter_pg' => $order->at_liter_gas,
                    'yn_pre_approve_fail' => 'D',
                    'dt_pre_approve_fail' => now()->format('Y-m-d H:i:s')
                ]))->save();
            })(),
            default => throw new Exception($response->message, $response->code)
        };
    }

    /**
     * @param Cancel $request
     * @return void
     * @throws AuthorizationException
     * @throws Exception
     */
    public static function cancel(Cancel $request): void
    {
        $response = new \App\Responses\Infine\Cancel(self::client(Method::POST, getenv('INFINE_PATH_CANCEL'), ['json' => $request]));

        match ($response->code == env('RETURN_TRUE') && $response->data->result_code == env('RETURN_TRUE')) {
            true => (function () use ($request, $response) {
                $order = (new OrderList())->findOrFail($response->data->no_order);
                $orderOil = (new OrderOilEventLog())->firstWhere([
                    'no_order' => $response->data->no_order,
                    'ds_credit_auth_no' => $response->data->no_approval
                ]);

                (new OrderOilEventLog([
                    'no_order' => $order->no_order,
                    'cd_order_event_type' => 614300,
                    'ds_credit_card_no' => $orderOil->ds_credit_card_no,
                    'ds_credit_auth_no' => $response->data->no_approval,
                ]))->save();
            })(),
            default => throw new Exception($response->message, $response->code)
        };
    }

    /**
     * @param Approval $request
     * @return void
     * @throws AuthorizationException
     * @throws Exception
     */
    public static function approval(Approval $request): void
    {
        $response = new \App\Responses\Infine\Approval(self::client(Method::POST, getenv('INFINE_PATH_APPROVAL'), ['json' => $request]));

        match ($response->code == env('RETURN_TRUE') && $response->data->resultCode == env('RETURN_TRUE')) {
            true => (function () use ($request, $response) {
                $order = (new OrderList())->findOrFail($response->data->noOrder);

                (new MemberShopEnterLog([
                    'ds_adver' => $order->ds_adver,
                    'no_shop' => $order->no_shop,
                    'no_order' => $order->no_order,
                    'yn_is_in' => 0
                ]))->save();
                (new OrderPayment())->firstWhere('no_order', $response->data->noOrder)->update([
                    'cd_pg_result' => 604100,
                    'cd_payment_status' => 603300
                ]);
            })(),
            default => throw new Exception($response->message, $response->code)
        };
    }

    /**
     * @param ApprovalResult $request
     * @return \App\Responses\Infine\ApprovalResult
     * @throws Exception
     */
    public static function approvalResult(ApprovalResult $request): \App\Responses\Infine\ApprovalResult
    {
        try {
            return new \App\Responses\Infine\ApprovalResult([
                'code' => '',
                'message' => '',
                'data' => [
                    'no_order' => $request->noOrder,
                    'infine_order' => $request->infineOrder
                ]
            ]);
        } catch (Throwable $t) {
            throw  new Exception($t->getMessage(), $t->getCode());
        }
    }
}
