<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\MemberLevel;
use App\Enums\Method;
use App\Enums\Pg;
use App\Models\MemberCard;
use App\Models\MemberCarinfo;
use App\Models\MemberParkingCoupon;
use App\Models\ParkingOrderList;
use App\Models\ParkingOrderProcess;
use App\Models\ParkingSite;
use App\Requests\AutoParking\CarEnter;
use App\Requests\AutoParking\CarExit;
use App\Requests\AutoParking\CheckFee;
use App\Requests\AutoParking\Payment;
use App\Requests\AutoParking\Refund;
use App\Requests\AutoParking\RegistCar;
use App\Utils\Code;
use App\Utils\Common;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use Owin\OwinCommonUtil\CodeUtil;
use Owin\OwinCommonUtil\Enums\ServiceCodeEnum;
use Owin\OwinCommonUtil\Enums\ServiceSchemaEnum;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class AutoParkingAbstract
{
    private static \App\Services\Dev\Pg|\App\Services\Production\Pg $pg;

    /**
     * @param Method $method
     * @param string $uri
     * @param array|null $options
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    private static function client(Method $method, string $uri, ?array $options = []): array
    {
        try {
            $response = Common::client($method, $uri, $options + self::getHeaders());
            $content = json_decode($response->getBody()->getContents(), true);
            Log::channel('client')->info(sprintf('%s %s RESPONSE ', $uri, $method->name), $content);

            return match ($response->getStatusCode()) {
                200 => match (data_get($content, 'resultCode')) {
                    '0000', '', null => $content,
                    default => throw new Exception(data_get($content, 'resultMessage'), (int)data_get($content, 'resultCode'))
                },
                400 => throw new BadRequestHttpException(message: data_get($content, 'resultMessage'), code: (int)data_get($content, 'resultCode')),
                401 => throw new AuthorizationException(message: data_get($content, 'resultMessage'), code: (int)data_get($content, 'resultCode')),
                default => throw new Exception(data_get($content, 'resultMessage'), (int)data_get($content, 'resultCode'))
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
     * @return array
     */
    #[ArrayShape(['headers' => "array"])]
    private static function getHeaders(): array
    {
        $timeStamp = now()->timestamp;
        return [
            'headers' => [
                'sitecode' => getenv('AUTO_PARKING_SITE_CODE'),
                'timestamp' => $timeStamp,
                'signature' => Common::getHash(sprintf('%s%s%s', getenv('AUTO_PARKING_SITE_CODE'), $timeStamp, getenv('AUTO_PARKING_SECRET_KEY')))
            ]
        ];
    }

    /**
     * @return array
     * @throws AuthorizationException
     */
    public static function parkingList(): array
    {
        return self::client(Method::GET, sprintf('%s%s', getenv('AUTO_PARKING_URI'), getenv('AUTO_PARKING_PATH_LIST')));
    }

    /**
     * @param RegistCar $request
     * @return array
     * @throws AuthorizationException
     */
    public static function registCar(RegistCar $request): array
    {
        return self::client(Method::POST, sprintf('%s%s', getenv('AUTO_PARKING_URI'), getenv('AUTO_PARKING_PATH_REGIST_CAR')), [
            'json' => $request
        ]);
    }

    /**
     * @param CheckFee $request
     * @return array
     * @throws AuthorizationException
     */
    public static function checkFee(CheckFee $request): array
    {
        return self::client(Method::POST, sprintf('%s%s', getenv('AUTO_PARKING_URI'), getenv('AUTO_PARKING_PATH_CHECK_FEE')), [
            'json' => $request
        ]);
    }

    /**
     * @param Payment $request
     * @return array
     * @throws AuthorizationException
     */
    public static function payment(Payment $request): array
    {
        return self::client(Method::POST, sprintf('%s%s', getenv('AUTO_PARKING_URI'), getenv('AUTO_PARKING_PATH_PAYMENT')), [
            'json' => $request
        ]);
    }

    /**
     * @param Refund $request
     * @return array
     * @throws AuthorizationException
     */
    public static function refund(Refund $request): array
    {
        return self::client(Method::POST, sprintf('%s%s', getenv('AUTO_PARKING_URI'), getenv('AUTO_PARKING_PATH_REFUND')), [
            'json' => $request
        ]);
    }

    /**
     * @param CarEnter $request
     * @return array
     * @throws AuthorizationException
     * @throws Exception|Throwable
     */
    public static function enter(CarEnter $request): array
    {
        foreach (array_reverse(ServiceSchemaEnum::cases()) as $schema) {
            DB::statement('use ' . $schema->value);

            $carInfo = (new MemberCarinfo())->with(['cards', 'member'])->where([
                ['ds_car_number', '=', str_replace(' ', '', $request->plateNumber)],
                ['yn_use_auto_parking', '=', 'Y'],
                ['no_card', '<>', null]
            ])->first();

            if ($carInfo) {
                $serviceCodeEnum = CodeUtil::getServiceCodeEnumFromSchema($schema->value);
                break;
            }
        }
        if (!$serviceCodeEnum) {
            throw new Exception('IF_0003', 9004);
        }

        $shop = (new ParkingSite)->where([
            'id_site' => $request->storeId
        ])->get()->whenEmpty(function () {
            throw new Exception('운영중지 매장입니다.');
        })->first();
        $shop['cd_pg'] = Pg::kcp->value;
        $serviceCodeEnum = match ($serviceCodeEnum == ServiceCodeEnum::OWIN) {
            true => (function () use ($carInfo) {
                return match (MemberLevel::from((int)$carInfo->member->cd_mem_level)) {
                    MemberLevel::AVN => match (empty(data_get($carInfo->member->detail, 'no_car_no_rsm')) == false) {
                        true => match ($carInfo->no == data_get($carInfo->member->detail, 'no_car_no_rsm')) {
                            true => ServiceCodeEnum::RENAULT,
                            default => ServiceCodeEnum::OWIN,
                        },
                        default => ServiceCodeEnum::OWIN
                    },
                    default => ServiceCodeEnum::OWIN
                };
            })(),
            default => $serviceCodeEnum
        };
        $appType = Common::getAppTypeFromServiceCodeEnum($serviceCodeEnum);
        $noOrder = CodeUtil::generateOrderCode($serviceCodeEnum);
        $nmOrder = "[자동결제] " . $shop['nm_shop'];
        $card = $carInfo->cards->where('cd_pg', $shop->cd_pg)->whenEmpty(function () {
            throw new Exception('IF_0003', 9999);
        })->first();

        try {
            (new ParkingOrderList([
                'no_order' => $noOrder,
                'nm_order' => $nmOrder,
                'no_user' => $carInfo->no_user,
                'ds_car_number' => $carInfo->ds_car_number,
                'seq' => $carInfo->seq,
                'no_site' => $shop->no_site,
                'id_site' => $shop->id_site,
                'id_auto_parking' => $shop->id_auto_parking,
                'no_card' => $card['no_card'],
                'cd_card_corp' => $card['cd_card_corp'],
                'no_card_user' => $card['no_card_user'],
                'cd_pg' => $shop->cd_pg,
                'at_disct' => 0,
                'at_cpn_disct' => 0,
                'at_basic_time' => $shop->at_basic_time,
                'at_basic_price' => $shop->at_basic_price,
                'dt_entry_time' => Carbon::parse($request->entryTime),
                'cd_service' => '900200',
                'cd_service_pay' => '901100',
                'cd_payment' => '501200',
                'cd_payment_kind' => '502100',
                'cd_payment_method' => '504100',
                'cd_third_party' => $appType->value,
                'ds_req_param' => ''
            ]))->saveOrFail();

            (new ParkingOrderProcess([
                'no_order' => $noOrder,
                'no_user' => $carInfo->no_user,
                'id_auto_parking' => $shop->id_auto_parking,
                'cd_order_process' => '616603', // 입차완료
            ]))->saveOrFail();
        } catch (Exception $e) {
            throw new Exception('IF_0003', 9999);
        }

        return [
            'interfaceCode' => "IF_0003",
            'resultMessage' => "",
            'resultCode' => '0000',
            'plateNumber' => $request->plateNumber,
            'txId' => $noOrder,
            'order' => (new ParkingOrderList())->with(['shop'])->firstWhere([
                'no_order' => $noOrder
            ])
        ];
    }

    /**
     * @param CarExit $request
     * @return array
     * @throws AuthorizationException
     * @throws Throwable
     */
    public static function exit(CarExit $request): array
    {
        self::$pg = Common::getService('Pg');

        $serviceSchemaEnum = CodeUtil::getServiceSchemaEnumFromOrderCode($request->txId);
        DB::statement('use ' . $serviceSchemaEnum->value);
        $order = (new ParkingOrderList())->with(['shop'])->where([
            'no_order' => $request->txId
        ])->get()->whenEmpty(function () use ($request) {
            throw new Exception($request->interfaceCode, 9011);
        }, function (Collection $order) use ($request) {
            if ($order->first()->cd_pg_result == '604100') {
                throw new Exception($request->interfaceCode, 9008);
            }
            if (!$order->first()->shop?->exists()) {
                throw new Exception($request->interfaceCode, 9004);
            }
        })->first();
        $order->shop->cd_pg = Pg::kcp->value;
        $carInfo = (new MemberCarinfo())->with(['cards', 'member'])->where([
            ['ds_car_number', '=', str_replace(' ', '', $request->plateNumber)],
            ['yn_use_auto_parking', '=', 'Y'],
            ['no_card', '<>', null]
        ])->get()->whenEmpty(function () {
            throw new Exception('IF_0005', 9004);
        })->first();

        (new ParkingOrderProcess([
            'no_order' => $request->txId,
            'no_user' => $carInfo->no_user,
            'id_site' => $order->shop->id_site,
            'id_auto_parking' => $order->shop->id_site,
            'cd_order_process' => '616604'
        ]))->saveOrFail();

        $verify = self::verifyAutoParkingOrder($order, $order->shop, $carInfo, $request);
        $parameter = new \App\Requests\Pg\Payment((new Request())->merge([
            'nmPg' => Pg::incarpayment_kcp->name,
            'noOrder' => $order->no_order,
            'noShop' => $order->shop->no_site,
            'noUser' => $carInfo->no_user,
            'nmBuyer' => $carInfo->member->nm_user,
            'email' => $carInfo->member->id_user,
            'phone' => $carInfo->member->ds_phone,
            'price' => $verify['paymentFee'],
            'atCupDeposit' => 0,
            'billkey' => $verify['card']->ds_billkey,
            'nmOrder' => $order->nm_order
        ]));

        $order->update([
            'dt_exit_time' => Carbon::parse($request->exitTime),
            'at_price' => $verify['paymentFee'],
            'at_price_pg' => $verify['paymentFee'],
        ]);
        $paymentInfo = match ($verify['paymentFee']) {
            0 => (new \App\Responses\Pg\Payment())->kcp($parameter, '0000', Code::message('auto-parking.0000'),  [
                'res_cd' => '0000',
                'res_msg' => Code::message('auto-parking.0000'),
                'tno' => '1',
                'ds_req_param' => $parameter,
                'ds_res_param' => [],
            ]),
            default => self::$pg::payment($parameter)
        };

        try {
            $order->update([
                'no_card' => $verify['card']->no_card,
                'cd_card_corp' => $verify['card']->cd_card_corp,
                'no_card_user' => $verify['card']->no_card_user,
                'dt_req' => now(),
                'dt_res' => now(),
                'ds_res_code' => $paymentInfo->res_cd,
                'ds_res_msg' => $paymentInfo->res_msg,
                'ds_req_param' => json_encode($paymentInfo->ds_req_param, JSON_UNESCAPED_UNICODE),
                'ds_res_param' => json_encode($paymentInfo->ds_res_param, JSON_UNESCAPED_UNICODE),
                'ds_server_reg' => now()->format('YmdHis'),
                'product_num' => 1,
                'dt_order_status' => now(),
                'dt_payment_status' => now(),
                'ds_res_order_no' => $paymentInfo->ds_res_order_no,
                'cd_order_status' => match ($paymentInfo->res_cd) {
                    '0000' => '601200',
                    default => '601900'
                },
                'cd_payment_status' => match ($paymentInfo->res_cd) {
                    '0000' => '603300',
                    default => '603200'
                },
                'cd_pg_result' => match ($paymentInfo->res_cd) {
                    '0000' => '604100',
                    default => '604999'
                },
                'cd_pg_bill_result' => match ($paymentInfo->res_cd) {
                    '0000' => '902000',
                    default => Code::pgResponse(sprintf('%s.code', $paymentInfo->res_cd)) ?? '902900',
                },
            ]);
            if ($paymentInfo->res_cd == '0000') {
                (new ParkingOrderProcess([
                    'no_order' => $order->no_order,
                    'no_user' => $carInfo->no_user,
                    'id_auto_parking' => $order->shop->id_auto_parking,
                    'cd_order_process' => '616600',
                ]))->saveOrFail();
            }
            self::payment(new Payment((new Request())->merge([
                'interfaceCode' => 'IF_0006', //인터페이스 코드
                'txId' => $request->txId,
                'storeId' => $order->shop->id_site,
                'storeCategory' => $order->shop->ds_category,
                'plateNumber' => $carInfo->ds_car_number,
                'approvalPrice' => $verify['encryptFee'],
                'approvalDate' => now()->format('Y-m-d H:i:s'),
                'approvalNumber' => $paymentInfo->res_cd == '0000' ? $paymentInfo->ds_res_order_no : '1',
                'approvalResult' => "1",
                'approvalMessage' => $paymentInfo->res_cd == '0000' ? "결제 완료" : $paymentInfo->res_msg,
            ])));
        } catch (Throwable $t) {
            Log::channel('error')->critical($t->getMessage(), [$t->getFile(), $t->getLine(), $t->getTraceAsString()]);
            if (in_array($order->cd_order_status, ['601900', '601950', '601999'])) {
                throw new Exception('취소된 주문정보입니다.');
            }

            $response = match ($order->at_price_pg > 0) {
                true => self::$pg::refund(new \App\Requests\Pg\Refund((new Request())->merge([
                    'nmPg' => Pg::incarpayment_kcp->name,
                    'noOrder' => $order->no_order,
                    'dsServerReg' => $order->ds_server_reg,
                    'nmOrder' => $order->nm_order,
                    'dsResOrderNo' => $order->ds_res_order_no,
                    'price' => $order->at_price_pg,
                    'reason' => $cdRejectReason ?? '회원취소'
                ]))),
                default => [
                    'res_cd' => '0000',
                    'res_msg' => Code::message('auto-parking.0000'),
                ]
            };

            $order->update([
                'ds_res_code_refund' => $response->res_cd,
                'dt_req_refund' => now(),
                'dt_res_refund' => now()
            ]);

            if ($response->res_cd === '0000') {
                self::refund(new Refund((new Request())->merge([
                    'interfaceCode' => 'IF_0007', //인터페이스 코드
                    'txId'        => $order->no_order,
                    'storeId'     => $order->shop->id_site,
                    'plateNumber' => $order->ds_car_number,
                    'cancelPrice' => (string)$order->at_price_pg,
                    'cancelDate'  => now()->format('YmdHis')
                ])));

                $order->update([
                    'cd_pickup_status'  => '602400',
                    'cd_payment_status' => '603900',
                    'cd_order_status'   => '601900',
                    'cd_reject_reason' => '',
                    'dt_order_status'   => now(),
                    'dt_pickup_status'  => now(),
                    'dt_payment_status' => now(),
                ]);

                (new ParkingOrderProcess([
                    'no_order' => $order->no_order,
                    'no_user' => $order->no_user,
                    'id_site' => $order->shop->id_site,
                    'no_parking_site' => $order->shop->id_site,
                    'cd_order_process' => '616601',
                ]))->saveOrFail();

                if ($order->at_cpn_disct > 0) {
                    (new MemberParkingCoupon())->where([
                        'no_user' => $order->no_user,
                        'no_order' => $order->no_order,
                    ])->update([
                        'cd_mcp_status' => '122100',
                        'at_price' => null,
                        'no_order' => null,
                        'dt_use' => null,
                    ]);
                }
            }

            $order->update([
                'cd_order_status' => '601900',
                'cd_payment_status' => '603200',
                'cd_pg_result' => '604999',
                'cd_pg_bill_result' => '902900',
            ]);
            (new ParkingOrderProcess([
                'no_order' => $order->no_order,
                'no_user' => $carInfo->no_user,
                'id_auto_parking' => $order->shop->id_auto_parking,
                'cd_order_process' => '616605',
            ]))->saveOrFail();
            self::payment(new Payment((new Request())->merge([
                'interfaceCode'   => 'IF_0006', //인터페이스 코드
                'txId' => $request->txId,
                'storeId' => $order->shop->id_site,
                'storeCategory' => $order->shop->ds_category,
                'plateNumber' => $carInfo->ds_car_number,
                'approvalPrice' => $verify['encryptFee'],
                'approvalDate' => now(),
                'approvalNumber' => $paymentInfo->res_cd == '0000' ? $paymentInfo->ds_res_order_no : '1',
                'approvalResult' => "0",
                'approvalMessage' => $t->getMessage()
            ])));

            return [
                'result' => false,
                'txId' => $request->txId,
                'code' => '9012',
                'pg_msg' => $paymentInfo->res_msg,
                'msg' => '결제가 정상적으로 이루어지지 않았습니다',
                'at_price_pg' => $verify['paymentFee'],
            ];
        }

        return [
            'result' => $paymentInfo->res_cd == '0000',
            'txId' => $request->txId,
            'pg_msg' => $paymentInfo->res_msg,
            'msg' => match ($paymentInfo->res_cd) {
                '0000' => '결제가 정상처리되었습니다.',
                default => '결제가 정상적으로 이루어지지 않았습니다',
            },
            'code' => $paymentInfo->res_cd,
            'no_user' => $carInfo->no_user,
            'at_price_pg' => $verify['paymentFee'],
            'order' => $order
        ];
    }

    /**
     * @param ParkingOrderList $order
     * @param ParkingSite $shop
     * @param MemberCarinfo $car
     * @param CarExit $request
     * @return array
     * @throws Exception
     */
    private static function verifyAutoParkingOrder(ParkingOrderList $order, ParkingSite $shop, MemberCarinfo $car, CarExit $request): array
    {
        if (Carbon::parse($request->entryTime) != $order->dt_entry_time) {
            throw new Exception("IF_0005", 9007);
        }

        //차량 정보의 카드 번호 확인
        if ($request->plateNumber != $order->ds_car_number) {
            throw new Exception('IF_0005', 9004);
        }

        $card = MemberCard::where([
            'no_card' => $car->no_card,
            'cd_pg' => $order->shop->cd_pg,
            'yn_delete' => 'N',
        ])->get()->whenEmpty(function () {
            throw new Exception("IF_0005", 9007);
        })->first();

        return [
            'card' => $card,
            'paymentFee' => (int)match (empty($request->paymentFee)) {
                true => $order->at_price,
                default => $request->paymentFee
            },
            'encryptFee' => $request->encryptFee,
        ];
    }
}
