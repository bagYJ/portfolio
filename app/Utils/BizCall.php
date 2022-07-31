<?php

declare(strict_types=1);

namespace App\Utils;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BizCall
{
    /**
     * bizcall api를 통하여 가상번호 리스트 조회
     * @return false|mixed
     * @throws GuzzleException
     */
    public static function getVns()
    {
        try {
            $client = new Client();
            $iid = Code::conf('virtual_number.id');
            $mmdd = date('md');

            $response = $client->post(Code::conf('virtual_number.uri') . Code::conf('virtual_number.path.list'), [
                'form_params' => [
                    'iid' => $iid,
                    'mmdd' => $mmdd,
                    'auth' => base64_encode(md5($iid . $mmdd))
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                if ($data['rt'] === 0) {
                    return $data;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * bizcall api를 통하여 가상번호 자동 설정(비어있는 가상번호 순차적으로 부여)
     * @param string $realNumber
     * @return false|mixed
     * @throws GuzzleException
     */
    public static function autoMapping(string $realNumber)
    {
        try {
            $client = new Client();
            $iid = Code::conf('virtual_number.id');

            $response = $client->post(Code::conf('virtual_number.uri') . "/link/auto_mapp.do", [
                'form_params' => [
                    'iid' => $iid,
                    'rn' => $realNumber,
                    'auth' => base64_encode(md5($iid . $realNumber))
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                if ($data['rt'] === 0) {
                    return $data;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public static function setVn($data)
    {
        try {
            $client = new Client();
            $iid = Code::conf('virtual_number.id');

            $response = $client->post(Code::conf('virtual_number.uri') . "/link/set_vn.do", [
                'form_params' => [
                    'iid' => $iid,
                    'vn' => $data['virtualNumber'],
                    'rn' => $data['realNumber'],
                    'auth' => base64_encode(md5($iid . $data['virtualNumber']))
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                if ($data['rt'] === 0) {
                    return $data;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
