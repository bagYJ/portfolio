<?php

declare(strict_types=1);

namespace App\Utils;

use App\Exceptions\OwinException;
use App\Services\RetailService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Cu
{
    /**
     * cu api 요청
     * @param string $callUrl
     * @param array $body
     * @param int $timeout
     * @return array
     * @throws GuzzleException
     */
    public static function send(string $callUrl, array $body, int $timeout = 3): array
    {
        $client = new Client();
        Log::channel('cu')->info('request : ', $body);
        $response = $client->post($callUrl, [
            'timeout' => $timeout,
            'body' => base64_encode(json_encode($body)),
        ]);
        $data = json_decode(base64_decode($response->getBody()->getContents()));
        Log::channel('cu')->info('response ' . $callUrl . ': ', (array)$data);

        return [
            'result' => $data?->result_code == '0000',
            ...(array)$data
        ];
    }

    /**
     * 주문번호 변환  ( 오윈 자체는 12자리, cu엔 오윈 12자리 중 앞자리 2 제외 11자리 )
     * @param string $type
     * @param string $noOrder
     * @return string
     */
    public static function changeNoOrder(string $type, string $noOrder): string
    {
        if ($type === 'CU') {
            return "2{$noOrder}";
        }
        return substr($noOrder, 1);
    }

    /**
     * hash 데이터 정확성 체크
     * @param string $sign
     * @param string $changeSign
     * @param array $request
     * @return void
     */
    public static function hashAccuracyCheck(string $sign, string $changeSign, array $request)
    {
        if ($sign !== $changeSign) {
            RetailService::insertRetailExternalResultLog($request, [
                'result' => false,
                'result_code' => '9998'
            ]);
            throw new OwinException(Code::message('9998'));
        }
    }

    /**
     * sign 생성
     * @param array $data
     * @return false|string
     */
    public static function generateSign(array $data)
    {
        return hash("sha256", implode("", $data));
    }

}
