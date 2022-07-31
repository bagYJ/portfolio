<?php

namespace App\Utils;

use App\Exceptions\MobilXException;
use App\Exceptions\OwinException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class AutoParking
{
    public static function parkingLotsList(): mixed
    {
        try {
            $client = new Client();

            $sitecode  = Code::conf('auto_parking.site_code');
            $timestamp = strtotime(date('Y-m-d H:i:s'));
            $secretKey = Code::conf('auto_parking.secret_key');

            $response = $client->get(
                Code::conf('auto_parking.api_uri') . '/parkingLotsList',
                [
                    'headers' => [
                        'sitecode'  => $sitecode,
                        'timestamp' => $timestamp,
                        'signature' => hash(
                            'sha256',
                            $sitecode . $timestamp . $secretKey
                        ),
                    ],
                ]
            );
            if ($response->getStatusCode() === 200) {
                $data = json_decode((string)$response->getBody(), true);
                if ($data['resultCode'] === '0000') {
                    return $data['parkingLotsList'];
                } else {
                    throw new MobilXException("IF_0001", $data['resultCode']);
                }
            }
            throw new MobilXException("IF_0001", 9999);
        } catch (Exception $e) {
            throw new MobilXException("IF_0001", 9999, null, $e->getMessage());
        }
    }

    /**
     * 자동결제 차량 등록/해제
     *
     * @param string $plateNumber : 차량번호
     * @param bool   $regType     : true(등록) / false(해제)
     *
     * @return mixed|null
     * @throws OwinException
     * @throws GuzzleException*@throws MobilXException
     */
    public static function registerCar(
        string $plateNumber,
        bool $regType = true
    ): mixed {
        try {
            $client = new Client();

            $sitecode  = Code::conf('auto_parking.site_code');
            $timestamp = strtotime(date('Y-m-d H:i:s'));
            $secretKey = Code::conf('auto_parking.secret_key');

            $response = $client->post(
                Code::conf('auto_parking.api_uri') . '/registerCar',
                [
                    'headers' => [
                        'content-type' => 'application/json;charset=UTF-8',
                        'sitecode'     => $sitecode,
                        'timestamp'    => $timestamp,
                        'signature'    => hash(
                            'sha256',
                            $sitecode . $timestamp . $secretKey
                        ),
                    ],
                    'json'    => [
                        'interfaceCode' => 'IF_0002',
                        'carList'       => [
                            [
                                'plateNumber' => $plateNumber,
                                'regType'     => $regType ? "1" : "0",
                            ]
                        ],
                    ],
                ]
            );
            if ($response->getStatusCode() === 200) {
                $data = json_decode((string)$response->getBody(), true);
                if (count($data['resultList'])) {
                    if ($data['interfaceCode'] === 'IF_0002') {
                        return $data['resultList'][0];
                    } else {
                        throw new OwinException(Code::message("AP{$data['resultCode']}"));
                    }
                }
            }
            if ($regType) {
                throw new OwinException(Code::message('AP0004'));
            }
            throw new OwinException(Code::message('AP0005'));
        } catch (Exception $e) {
            throw new OwinException(Code::message('AP0005') .PHP_EOL. $e->getMessage());
        }
    }

    /**
     * 주차 비용 조회 요청
     *
     * @param string $plateNumber : 차량번호
     * @param string $storeId     : 주차장 ID
     * @param string $txId        : 거래 일련번호(입차때 발급한 일련번호)
     *
     * @return array
     * @throws OwinException
     * @throws GuzzleException*@throws MobilXException
     */
    public static function checkFee(
        string $plateNumber,
        string $storeId,
        string $txId
    ): array {
        try {
            $client = new Client();

            $sitecode  = Code::conf('auto_parking.site_code');
            $timestamp = strtotime(date('Y-m-d H:i:s'));
            $secretKey = Code::conf('auto_parking.secret_key');

            $response = $client->post(
                Code::conf('auto_parking.api_uri') . '/checkFee',
                [
                    'headers' => [
                        'content-type' => 'application/json;charset=UTF-8',
                        'sitecode'     => $sitecode,
                        'timestamp'    => $timestamp,
                        'signature'    => hash(
                            'sha256',
                            $sitecode . $timestamp . $secretKey
                        ),
                    ],
                    'json'    => [
                        'interfaceCode' => 'IF_0004',
                        'plateNumber'   => $plateNumber,
                        'storeId'       => $storeId,
                        'txId'          => $txId,
                    ],
                ]
            );
            if ($response->getStatusCode() === 200) {
                $data = json_decode((string)$response->getBody(), true);
                if ($data['interfaceCode'] === 'IF_0004' && $data['code'] === '0000') {
                    return [
                        'fee'   => self::decryptFee($data['paymentFee']), //todo 데이터 복호화
                        'hours' => $data['parkedHours'],
                    ];
                } else {
                    throw new OwinException(Code::message('AP9012'));
                }
            }
            throw new OwinException(Code::message('AP9012'));
        } catch (Exception $e) {
            throw new OwinException(Code::message('AP9012') . "::" . $e->getMessage());
        }
    }

    /**
     * 결제 완료 정보 전달
     *
     * @param array $request
     *
     * @return array
     * @throws OwinException
     * @throws GuzzleException*@throws MobilXException
     */
    public static function resultPayment(array $request): array
    {
        try {
            $client   = new Client();

            $sitecode  = Code::conf('auto_parking.site_code');
            $timestamp = strtotime(date('Y-m-d H:i:s'));
            $secretKey = Code::conf('auto_parking.secret_key');

            $response = $client->post(Code::conf('auto_parking.api_uri') . '/resultPayment', [
                'headers' => [
                    'content-type' => 'application/json;charset=UTF-8',
                    'sitecode'     => $sitecode,
                    'timestamp'    => $timestamp,
                    'signature'    => hash(
                        'sha256',
                        $sitecode . $timestamp . $secretKey
                    ),
                ],
                'json' => [
                    'interfaceCode'   => 'IF_0006', //인터페이스 코드
                    'txId'            => $request['txId'], //no_order
                    'storeId'         => $request['storeId'], //주차장 ID
                    'storeCategory' => $request['storeCategory'], //주차장 분류
                    'plateNumber'     => $request['plateNumber'], //차량번호
                    'approvalPrice'   => $request['approvalPrice'] ?? null, //승인금액
                    'approvalDate'    => $request['approvalDate'] ?? null, //승인일시
                    'approvalNumber'  => $request['approvalNumber'] ?? null, //승인번호
                    'approvalResult'  => $request['approvalResult'], //승인 실패/성공
                    'approvalMessage' => $request['approvalMessage'], //결과 메시지
                ],
            ]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode((string)$response->getBody(), true);
                if ($data['interfaceCode'] === 'IF_0006' && $data['resultCode'] == '0000') {
                    return $data;
                } else {
                    throw new OwinException(Code::message('AP0006'));
                }
            }
            throw new OwinException(Code::message('AP0006'));
        } catch (Exception $e) {
            Log::channel('error')->error('[AP0006] auto parking resultPayment error', [$e->getMessage()]);
            throw new OwinException(Code::message('AP0006'));
        }
    }

    public static function refund(array $request): array
    {
        try {
            $client   = new Client();

            $sitecode  = Code::conf('auto_parking.site_code');
            $timestamp = strtotime(date('Y-m-d H:i:s'));
            $secretKey = Code::conf('auto_parking.secret_key');

            $response = $client->post(Code::conf('auto_parking.api_uri') . '/refund', [
                'headers' => [
                    'content-type' => 'application/json;charset=UTF-8',
                    'sitecode'     => $sitecode,
                    'timestamp'    => $timestamp,
                    'signature'    => hash(
                        'sha256',
                        $sitecode . $timestamp . $secretKey
                    ),
                ],
                'json' => [
                    'interfaceCode'   => 'IF_0007', //인터페이스 코드
                    'txId'            => $request['txId'], //no_order
                    'storeId'         => $request['storeId'], //주차장 ID
                    'plateNumber'     => $request['plateNumber'], //차량번호
                    'cancelPrice'  => self::encryptFee($request['cancelPrice']), //승인 실패/성공
                    'cancelDate' => $request['cancelDate'], //결과 메시지
                ],
            ]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode((string)$response->getBody(), true);
                if ($data['interfaceCode'] === 'IF_0007' && $data['resultCode'] == '0000') {
                    return $data;
                } else {
                    throw new MobilXException("IF_0006", 9999, null, $data['approvalMessage']);
                }
            }
            throw new MobilXException("IF_0006", 9999);
        } catch (Exception $e) {
            throw new MobilXException("IF_0006", 9999, null, $e->getMessage());
        }
    }

    public static function encryptFee($data): string
    {
        $iv = '4vf64wv0pj5wqwb2';
        $key = 'ln1crdtmbbh297294cakp2g3qlpm9no6';
        return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    public static function decryptFee($data): string
    {
        $iv = '4vf64wv0pj5wqwb2';
        $key = 'ln1crdtmbbh297294cakp2g3qlpm9no6';
        return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}
