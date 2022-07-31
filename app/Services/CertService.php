<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CertNation;
use App\Enums\Sex;
use App\Exceptions\OwinException;
use App\Models\MemberOwnAuthlog;
use App\Utils\Code;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class CertService extends Service
{
    public static function request(array $request): array
    {
        if (self::requestCount()->count() > 100) {
            throw new OwinException(Code::message('M1129'));
        }

        $parameter = [
            'cpId' => Code::conf('kmcert.id'),
            'urlCode' => '003001',
            'certNum' => makePaymentNo(),
            'date' => now()->format('YmdHis'),
            'phoneNo' => $request['pm_phone'],
            'phoneCorp' => $request['pm_agency'],
            'birthDay' => $request['pm_birth'],
            'gender' => Sex::case($request['pm_sex'])->value,
            'nation' => CertNation::case($request['pm_nation'])->value,
            'name' => str_replace(' ', '+', iconv('UTF-8', 'CP949', $request['pm_name'])),
            'extendVar' => Code::conf('kmcert.extend_var')
        ];
        $response = self::cert(Code::conf('kmcert.uri') . Code::conf('kmcert.send_path'), $parameter);

        self::setMemberOwnAuthlog([
            'no_auth_seq' => $parameter['certNum'],
            'yn_nation' => $request['pm_nation'],
            'ds_name' => $request['pm_name'],
            'ds_phone_agency' => $parameter['phoneCorp'],
            'ds_phone' => $parameter['phoneNo'],
            'ds_sex' => $request['pm_sex'],
            'ds_birthday' => $parameter['birthDay'],
            'ds_socket_result1' => $response[9],
            'ds_auth_result1' => $response[8],
            'ds_request_check1' => $response[10],
            'ds_request_check2' => $response[11],
            'ds_request_check3' => $response[12],
            'ds_request_ip' => getenv('REMOTE_ADDR'),
            'ct_request' => 1
        ]);

        return $response;
    }

    public static function retry(MemberOwnAuthlog $authlog): array
    {
        $parameter = [
            'certNum' => $authlog->no_auth_seq,
            'check1' => $authlog->ds_request_check1,
            'check2' => $authlog->ds_request_check2,
            'check3' => $authlog->ds_request_check3,
            'extendVar' => Code::conf('kmcert.extend_var'),
        ];
        $response = self::cert(Code::conf('kmcert.uri') . Code::conf('kmcert.retry_path'), $parameter);

        $authlog->updateOrFail([
            'ds_request_check1' => $response[3],
            'ds_request_check2' => $response[4],
            'ds_request_check3' => $response[5],
            'ds_socket_result1' => $response[2],
            'ds_auth_result1' => $response[1],
            'ct_request' => $authlog->ct_request + 1,
        ]);

        return $response;
    }

    public static function complete(string $smsNum, MemberOwnAuthlog $authlog): Collection
    {
        $parameter = [
            'certNum' => $authlog->no_auth_seq,
            'smsNum' => $smsNum,
            'check1' => $authlog->ds_request_check1,
            'check2' => $authlog->ds_request_check2,
            'check3' => $authlog->ds_request_check3,
            'extendVar' => Code::conf('kmcert.extend_var'),
        ];

        $response = self::cert(Code::conf('kmcert.uri') . Code::conf('kmcert.complete_path'), $parameter);
        if (empty($response[1]) === false) {
            $response[1] = ICertSeed(2, 0, $parameter['certNum'], $response[1]);
        }
        if (empty($response[2]) === false) {
            $response[2] = ICertSeed(2, 0, $parameter['certNum'], $response[2]);
        }

        $authlog->updateOrFail([
            'ds_request_check1' => $response[5],
            'ds_request_check2' => $response[6],
            'ds_request_check3' => $response[7],
            'ds_ci' => $response[1],
            'ds_di' => $response[2],
            'ds_socket_result2' => $response[4],
            'ds_auth_result2' => $response[3],
            'dt_complate' => now(),
            'ct_complate' => $authlog->ct_complate + 1,
        ]);

        return MemberService::getMember([
            'ds_ci' => $response[1]
        ])->whenNotEmpty(function ($member) {
            if (empty($member->id_user) === false && $member->ds_status == 'N') {
                throw new OwinException(Code::message('M1413'));
            }
        });
    }

    private static function cert(string $path, array $request): array
    {
        if (extension_loaded('icertsecu') === false) {
            throw new OwinException(Code::message('M1127'));
        }

        $cert = implode(Code::conf('kmcert.separator'), $request);
        //04. 1차암호화
        $encCert = ICertSeed(1, 0, '', $cert);
        //05. 변조검증값 생성
        $encCertHash = str_repeat('abcd', 10);
        //06. 2차암호화
        $encCert = implode(Code::conf('kmcert.separator'), [$encCert, $encCertHash, $request['extendVar']]);
        $encCert = ICertSeed(1, 0, '', $encCert);

        $response = (new Client())->get($path, [
            'query' => [
                'tr_cert' => $encCert,
            ]
        ]);
        $content = $response->getBody()->getContents();
        if (strlen($content) == 8) {
            throw new OwinException(Code::message('M1128'));
        }

        $decRetInfo = ICertSeed(2, 0, $request['certNum'], $content);
        $totInfo = explode(Code::conf('kmcert.separator'), $decRetInfo);
        $decRetInfo = ICertSeed(2, 0, $request['certNum'], $totInfo[0]);
        if (in_array($decRetInfo, Code::conf('kmcert.error_code'))) {
            throw new OwinException(Code::message($decRetInfo));
        }

        return explode(Code::conf('kmcert.separator'), $decRetInfo);
    }

    private static function requestCount(): Collection
    {
        return MemberOwnAuthlog::where('ds_request_ip', getenv('REMOTE_ADDR'))
            ->whereBetween('dt_reg', [now(), now()->addDays(-1)])->get();
    }

    public static function setMemberOwnAuthlog(array $parameter): void
    {
        (new MemberOwnAuthlog($parameter))->saveOrFail();
    }

    public static function getMemberOwnAuthlog(array $parameter): Collection
    {
        return MemberOwnAuthlog::where($parameter)->get();
    }
}
