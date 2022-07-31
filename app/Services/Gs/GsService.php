<?php

namespace App\Services\Gs;

use App\Exceptions\OwinException;
use App\Utils\Code;
use App\Utils\Common;
use App\Utils\Encrypt;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GsService
{
    /**
     * 쿠폰 발급
     *
     * @param string $interworkCode
     * @param string $paymentNo : 발급 번호
     *
     * @return array|null
     */
    public static function issue(string $interworkCode, string $paymentNo): ?array
    {
        $gsKey = Code::conf('nusoap.gs_key');
        $gsIv = Code::conf('nusoap.gs_iv');
        $gsWsdl = Code::conf('nusoap.gs_wsdl');
        $parameter = 'Req_Div_Cd=01&Issu_Req_Val=' . $interworkCode . '&Clico_Issu_Paym_No=' . $paymentNo . '&Clico_Issu_Paym_Seq=1&Cre_Cnt=1&Avl_Div_Cd=02';
        $encrypt = Encrypt::encrypt($parameter, $gsKey, $gsIv);

        try {
            $client = new Client();
            $response = $client->post($gsWsdl . "/CouponIssue", [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'query' => [
                    'Clico_Cd' => Code::conf('nusoap.gs_company_code'),
                    'EncStr' => $encrypt
                ],
                'timeout' => 3
            ]);
            $data = Common::xmlToJson($response->getBody());
            if ($response->getStatusCode() === 200) {
                if ($data) {
                    $result = [
                        'returnCode' => $data['return']['returnCode'],
                        'returnMsg' => $data['return']['returnMsg'],
                        'Issu_Req_Val' => $interworkCode,
                    ];
                    if ($data['return']['couponInfo']) {
                        $result['couponInfo'] = [
                            'avl_Start_Dy' => $data['return']['couponInfo']['avl_Start_Dy'],
                            'avl_End_Dy' => $data['return']['couponInfo']['avl_End_Dy'],
                            'cupn_No' => Encrypt::decrypt($data['return']['couponInfo']['cupn_No'], $gsKey, $gsIv),
                        ];
                    }
                    Log::channel('response')->info('gs coupon issue response: ', $data);
                    return $result;
                }
            } else {
                Log::channel('error')->error('gs coupon issue error: ', $data);
                throw new OwinException(Code::message('P2300'));
            }
            return null;
        } catch (Exception $e) {
            Log::channel('error')->error('gs coupon issue error: ', ['message' => $e->getMessage()]);
            throw new OwinException(Code::message('P2300'));
        }
    }

    /**
     * 쿠폰 조회
     *
     * @param string $interworkCode
     * @param string $couponNo
     *
     * @return array|null
     */
    public static function search(string $interworkCode, string $couponNo): ?array
    {
        $gsKey = Code::conf('nusoap.gs_key');
        $gsIv = Code::conf('nusoap.gs_iv');
        $gsWsdl = Code::conf('nusoap.gs_wsdl');
        $parameter = 'Req_Div_Cd=01&Issu_Req_Val=' . $interworkCode . '&Search_Div=01&Receive_Div=99&Cupn_No=' . $couponNo;
        $encrypt = Encrypt::encrypt($parameter, $gsKey, $gsIv);

        try {
            $client = new Client();
            $response = $client->post($gsWsdl . "/CouponSearch", [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'query' => [
                    'Clico_Cd' => Code::conf('nusoap.gs_company_code'),
                    'EncStr' => $encrypt
                ],
                'timeout' => 3
            ]);
            $data = Common::xmlToJson($response->getBody());
            if ($response->getStatusCode() === 200) {
                if ($data) {
                    parse_str(Encrypt::decrypt($data['return']['encOut'], $gsKey, $gsIv), $couponInfo);
                    $result = [
                        'returnCode' => $data['return']['returnCode'],
                        'returnMsg' => $data['return']['returnMsg'],
                        'couponInfo' => [
                            'REQDOC_IDX' => $couponInfo['REQDOC_IDX'],
                            'CUPN_NO' => $couponInfo['CUPN_NO'],
                            'PROD_CD' => $couponInfo['PROD_CD'],
                            'PROD_NM' => $couponInfo['PROD_NM'],
                            'MCHT_CD' => $couponInfo['MCHT_CD'],
                            'MCHT_NM' => $couponInfo['MCHT_NM'],
                            'USE_YN' => $couponInfo['USE_YN'],
                            'USE_DT' => $couponInfo['USE_DT'],
                            'ISSU_CNCL_YN' => $couponInfo['ISSU_CNCL_YN'],
                            'ISSU_CNCL_DT' => $couponInfo['ISSU_CNCL_DT'],
                            'AVL_START_DY' => $couponInfo['AVL_START_DY'],
                            'AVL_END_DY' => $couponInfo['AVL_END_DY'],
                            'CUPN_RAMT' => $couponInfo['CUPN_RAMT'],
                            'REG_DT' => $couponInfo['REG_DT'],
                            'STATE' => $couponInfo['STATE'],
                            'FAMT_AMT' => $couponInfo['FAMT_AMT'],
                            'TOT_USABLE_CNT' => $couponInfo['TOT_USABLE_CNT'],
                            'USE_UNIT_AMT' => $couponInfo['USE_UNIT_AMT'],
                            'USE_CNT' => $couponInfo['USE_CNT'],
                            'USE_AMT' => $couponInfo['USE_AMT'],
                        ]
                    ];
                    Log::channel('response')->info('gs coupon search response: ', $data);
                    return $result;
                }
            } else {
                Log::channel('error')->error('gs coupon search error: ', $data);
                throw new OwinException(Code::message('P2300'));
            }
            return null;
        } catch (Exception $e) {
            Log::channel('error')->error('gs coupon search error: ', ['message' => $e->getMessage()]);
            throw new OwinException(Code::message('P2300'));
        }
    }

    public static function cancel($interworkCode, $couponNo)
    {
        $gsKey = Code::conf('nusoap.gs_key');
        $gsIv = Code::conf('nusoap.gs_iv');
        $gsWsdl = Code::conf('nusoap.gs_wsdl');

        $parameter = 'Req_Div_Cd=01&Issu_Req_Val=' . $interworkCode . '&Cncl_Req_Div=01&Cupn_No=' . $couponNo;
        $encrypt = Encrypt::encrypt($parameter, $gsKey, $gsIv);

        try {
            $client = new Client();
            $response = $client->post($gsWsdl . "/CouponCancel", [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'query' => [
                    'Clico_Cd' => Code::conf('nusoap.gs_company_code'),
                    'EncStr' => $encrypt
                ],
                'timeout' => 3
            ]);
            $data = Common::xmlToJson($response->getBody());
            if ($response->getStatusCode() === 200) {
                return [
                    'returnCode' => $data['return']['returnCode'],
                    'returnMsg' => $data['return']['returnMsg'],
                    'cancelDate' => Encrypt::decrypt($data['return']['encOut']['Issu_Cncl_Dt'], $gsKey, $gsIv),
                ];
            } else {
                Log::channel('error')->error('gs coupon cancel error: ', $data);
                throw new OwinException(Code::message('P2300'));
            }
        } catch (Exception $e) {
            Log::channel('error')->error('gs coupon cancel error: ', ['message' => $e->getMessage()]);
            throw new OwinException(Code::message('P2300'));
        }
    }
}
