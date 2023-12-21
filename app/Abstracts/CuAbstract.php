<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\AlarmEventType;
use App\Enums\Method;
use App\Enums\OrderStatus;
use App\Enums\Pg;
use App\Exceptions\CustomCuException;
use App\Models\MemberRetailCoupon;
use App\Models\MemberShopRetailLog;
use App\Models\OrderList;
use App\Models\OrderProcess;
use App\Models\RetailAdminChkLog;
use App\Models\RetailExternalApiLog;
use App\Requests\Cu\{ArrivalAlarm, CancelCheck, DeliveryAlarm, Order, OrderCancel, OrderConfirm, ProductCheck};
use App\Requests\Pg\Refund;
use App\Utils\Code;
use App\Utils\Common;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class CuAbstract
{
    private static \App\Services\Dev\Pg|\App\Services\Production\Pg $pg;

    /**
     * @param string $path
     * @param array $options
     * @return array
     * @throws Exception
     */
    public static function client(string $path, array $options): array
    {
        try {
            $response = Common::client(Method::POST, sprintf('%s%s', getenv('CU_URI'), $path), $options);
            $content = json_decode(base64_decode($response->getBody()->getContents()), true);
            Log::channel('client')->info(sprintf('%s %s RESPONSE ', sprintf('%s%s', getenv('CU_URI'), $path), Method::POST->name), $content);

            return match (data_get($content, 'result_code')) {
                getenv('RETURN_TRUE') => $content,
                default => throw new BadRequestHttpException(message: data_get($content, 'result_msg') ?? data_get($content, 'result_code'), code: (int)data_get($content, 'result_code'))
            };
        } catch (BadRequestHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }

    /**
     * @param ProductCheck $request
     * @return array
     * @throws Exception
     */
    public static function productCheck(ProductCheck $request): array
    {
        return self::client( getenv('CU_PATH_PRODUCT_CHECK'), [
            'body' => base64_encode(json_encode($request))
        ]);
    }

    /**
     * @param CancelCheck $request
     * @return array
     * @throws Exception
     */
    public static function cancelCheck(CancelCheck $request): array
    {
        return self::client( getenv('CU_PATH_CANCEL_CHECK'), [
            'body' => base64_encode(json_encode($request))
        ]);
    }

    /**
     * @param OrderCancel $request
     * @return array
     * @throws Exception
     */
    public static function orderCancel(OrderCancel $request): array
    {
        return self::client( getenv('CU_PATH_REFUND'), [
            'body' => base64_encode(json_encode($request))
        ]);
    }

    /**
     * @param ArrivalAlarm $request
     * @return array
     * @throws Exception
     */
    public static function arrivalAlarm(ArrivalAlarm $request): array
    {
        return self::client( getenv('CU_PATH_ARRIVAL_ALARM'), [
            'body' => base64_encode(json_encode($request))
        ]);
    }

    /**
     * @param DeliveryAlarm $request
     * @return array
     * @throws Exception
     */
    public static function deliveryAlarm(DeliveryAlarm $request): array
    {
        return self::client( getenv('CU_PATH_DELIVERY_ALARM'), [
            'body' => base64_encode(json_encode($request))
        ]);
    }

    /**
     * @param Order $request
     * @return array
     * @throws Exception
     */
    public static function order(Order $request): array
    {
        return self::client( getenv('CU_PATH_ORDER'), [
            'body' => base64_encode(json_encode($request))
        ]);
    }

    /**
     * @param string $noOrder
     * @param string $partnerCode
     * @param string $shopCode
     * @param array $update
     * @param string $json
     * @return array
     * @throws CustomCuException
     */
    public static function updateOrder(string $noOrder, string $partnerCode, string $shopCode, array $update, string $json): array
    {
        try {
            $order = self::getOrder($noOrder);
            if ($order->cd_order_status >= OrderStatus::MEMBER_CANCEL->value) {
                throw new Exception(message: Code::message('9970'), code: 9970);
            }
            $order->update($update);

            (new MemberShopRetailLog())->create([
                'no_user' => $order->no_user,
                'no_shop' => $order->no_shop,
                'no_order' => $order->no_order,
                'cd_alarm_event_type' => data_get($update, 'cd_alarm_event_type')
            ]);

            return [
                'partner_code' => $partnerCode,
                'shop_code' => $shopCode,
                'result_code' => env('RETURN_TRUE'),
                'result_msg' => '성공'
            ];
        } catch (Throwable $e) {
            self::registRetailExternalApiLog($partnerCode, $shopCode, $e->getMessage(), $e->getCode(), $json);

            throw new CustomCuException($partnerCode, $shopCode, $e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $noOrder
     * @return OrderList
     */
    public static function getOrder(string $noOrder): OrderList
    {
        return (new OrderList)->findOrFail($noOrder);
    }

    /**
     * @param string $noOrder
     * @return int
     */
    public static function getOrderCompleteLogCnt(string $noOrder): int
    {
        return (new MemberShopRetailLog)->where([
            'no_order' => $noOrder,
            'cd_alarm_event_type' => AlarmEventType::COMPLETE_PICKUP->value
        ])->count();
    }

    /**
     * @param array $data
     * @return void
     */
    public static function registMemberShopRetailLog(array $data): void
    {
        (new MemberShopRetailLog($data))->save();
    }

    /**
     * @param array $data
     * @return void
     */
    public static function registRetailAdminChkLog(array $data): void
    {
        (new RetailAdminChkLog($data))->save();
    }

    /**
     * @param string $partnerCode
     * @param string $shopCode
     * @param string $message
     * @param string|int $code
     * @param string|null $json
     * @return void
     */
    public static function registRetailExternalApiLog(string $partnerCode, string $shopCode, string $message, string|int $code, ?string $json = null): void
    {
        (new RetailExternalApiLog([
            'api_url' => getenv('HTTP_HOST') . getenv('REQUEST_URI'),
            'dt_request' => now(),
            'result_code' => $code,
            'result_msg' => $message,
            'request_param' => $json,
            'response_param' => json_encode([
                'partner_code' => $partnerCode,
                'shop_code' => $shopCode,
                'result_code' => $code,
                'result_msg' => $message
            ])
        ]))->save();
    }

    /**
     * @param OrderConfirm $request
     * @param OrderList|Model $order
     * @return array
     * @throws Throwable
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function orderRefund(OrderConfirm $request, OrderList|Model $order): array
    {
        self::$pg = Common::getService('Pg');

        if (!$order->shop?->store_cd) {
            (new RetailExternalApiLog())->create([
                'api_url' => env('HTTP_HOST') . env('REQUEST_URI'),
                'dt_request' => date('Y-m-d H:i:s'),
                'result_code' => 'M1303',
                'result_msg' => '매장번호가 없습니다.',
                'request_param' => json_encode($request),
                'ori_request' => base64_encode(json_encode($request)),
                'response_param' => null,
                'ori_response' => null,
            ]);
            throw new Exception('매장번호가 없습니다.');
        }

        if (in_array($order->cd_order_status, ['601900', '601950', '601999'])) {
            throw new Exception('취소된 주문정보입니다.');
        }

        $response = match ($order->at_price_pg) {
            0 => (new \App\Responses\Pg\Refund())->kcp('0000', '정상처리'),
            default => self::$pg::refund(new Refund((new Request())->merge([
                'nmPg' => match ($order->cd_pg) {
                    500600 => Pg::incarpayment_kcp->name,
                    default => Pg::from((int)$order->cd_pg)->name
                },
                'dsResOrderNo' => $order->orderPayment->ds_res_order_no,
                'price' => $order->at_price_pg,
                'reason' => '매장취소',
                'noOrder' => $order->no_order,
                'dsServerReg' => $order->orderPayment->ds_server_reg,
                'nmOrder' => $order->nm_order
            ])))
        };

        $order->orderPayment->update([
            'ds_res_code_refund' => $response->res_cd,
            'cd_reject_reason' => '606630',
            'dt_req_refund' => now(),
            'dt_res_refund' => now()
        ]);

        if ($response->res_cd == '0000') {
            $order->update([
                'cd_pickup_status' => '602400',
                'cd_payment_status' => '603900',
                'cd_order_status' => '601950',
                'dt_order_status' => now(),
                'dt_pickup_status' => now(),
                'dt_payment_status' => now(),
            ]);

            (new OrderProcess([
                'no_order' => $order->no_order,
                'no_user' => $order->no_user,
                'no_shop' => $order->no_shop,
                'cd_order_process' => '616991',
            ]))->saveOrFail();
            (new MemberRetailCoupon())->where([
                'no_user' => $order->no_user,
                'no_order_retail' => $order->no_order
            ])->update([
                'use_coupon_yn' => 'Y',
                'cd_mcp_status' => '122100',
                'dt_use' => null,
                'no_order_retail' => null
            ]);
        }

        return [
            'partner_code' => $request->partner_code,
            'shop_code' => $request->shop_code,
            'result_code' => env('RETURN_TRUE'),
            'result_msg' => '성공'
        ];
    }
}
