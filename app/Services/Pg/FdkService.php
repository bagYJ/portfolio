<?php

declare(strict_types=1);

namespace App\Services\Pg;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Interface\PgInterface;
use App\Services\Pg\Fdk\Fdk;
use App\Utils\Code;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FdkService extends PgService implements PgInterface
{
    private Fdk $fdk;
    private string $pubKey;

    public function __construct()
    {
        parent::__construct('fdk');
        $this->fdk = new Fdk();
        $this->pubKey = file_get_contents(storage_path() . $this->pgInfo['pub_key']);
    }

    public function request(array $request): array
    {
        $OrderID = Auth::id() . substr($request['no_cardnum'], -4) . time();
        $parameter = [
            'MxID' => $this->pgInfo['mxid_billkey'],
            'PayMethod' => 'CC',
            'CcMode' => '11',
            'SpecVer' => 'F101C000',
            'OrderID' => $OrderID,
            'EncodeType' => 'U',
            'CcNO' => $this->rsaEncData($request['no_cardnum'], $this->pubKey),
            'CcExpDate' => substr(date('Y'), 0, 2) . $request['no_expyea'] . $request['no_expmon'],
            'CcVfNO' => $this->rsaEncData(
                match (empty($request['no_biz'])) {
                    true => substr(Auth::user()->ds_birthday, 2, 6),
                    false => $request['no_biz']
                },
                $this->pubKey
            ),
            'CcVfValue' => $this->rsaEncData($request['no_pin'], $this->pubKey),
            'CcNameOnCard' => Auth::user()->nm_user,
            'PhoneNO' => '',
            'FDHash' => strtoupper(
                hash('sha256', $this->pgInfo['mxid_billkey'] . $OrderID . $this->pgInfo['keydata_billkey'])
            )
        ];
        $response = $this->setPayment(
            $this->pgInfo['auth_send_path'],
            $parameter + [
                'MxIssueNO' => Auth::id() . (time() + 1000000000) . mt_rand(100, 999),
                'TxCode' => 'EC139000'
            ]
        );

        if ($response['ReplyCode'] != '0000') {
            $response = $this->setPayment(
                $this->pgInfo['auth_send_path'],
                $parameter + [
                    'MxIssueNO' => '',
                    'TxCode' => 'EC139200'
                ]
            );
        }
        Log::channel('response')->info('fdk request response: ', parameterReplace($response));

        $cardResult = $this->hasBillkey(Auth::id(), $response['BillKey'], EnumYN::N, $this->pgInfo['code']);
        if ($cardResult === true) {
            throw new OwinException(Code::message('P1023'));
        }

        $this->setMemberCardRequest(
            noUser: Auth::id(),
            dsFdkHash: $response,
            dsOwinHash: [$parameter['FDHash']],
            cdCardRegist: $response['ReplyCode'],
            dsResParam: $response
        );

        return [
            'result_msg' => $response['ReplyMessage'], //
            'result_code' => $response['ReplyCode'], //
            'ds_billkey' => $response['BillKey'], //
            'cd_pg' => $this->pgInfo['code'], //
            'cd_card_corp' => $response['IssCD'], //
            'no_card_user' => $response['CcNO'], //
            'res_param' => $response, //
            'ds_res_order_no' => $response['AuthNO'] ?? '', //
            'yn_credit' => $response['CheckYn'], //
        ];
    }

    public function payment(array $request): array
    {
        $response = $this->setPayment($this->pgInfo['cert_send_path'], [
            'MxID' => $this->pgInfo['mxid_billkey'],
            'MxIssueNO' => $request['no_order'],
            'MxIssueDate' => now()->format('YmdHis'),
            'PayMethod' => 'CC',
            'CcMode' => '10',
            'EncodeType' => 'U',
            'SpecVer' => 'F100C000',
            'TxCode' => 'EC132000',
            'Amount' => $request['at_price_pg'],
            'Currency' => 'KRW',
            'Tmode' => 'WEB',
            'Installment' => '00',
            'BillType' => '00',
            'CcNameOnCard' => $request['nm_user'],
            'CcProdDesc' => sprintf('%s_%s', $request['no_shop'], $request['nm_order']),
            'PhoneNO' => $request['ds_phone'],
            'Email' => $request['id_user'],
            'BillKey' => $request['ds_billkey'],
            'FDHash' => strtoupper(
                hash(
                    "sha256",
                    $this->pgInfo['mxid_billkey'] . $request['no_order'] . $request['at_price_pg'] . $this->pgInfo['keydata_billkey']
                )
            ),
        ]);
        Log::channel('response')->info('fdk payment response: ', parameterReplace($response));

        return [
            'res_cd' => $response['ReplyCode'],
            'res_msg' => $response['ReplyMessage'],
            'ds_res_order_no' => $response['AuthNO'],
            'at_price_pg' => $response['Amount'],
            'ds_req_param' => $request,
            'ds_res_param' => $response,
        ];
    }

    public function refund(array $orderList, string $reason): array
    {
        $response = $this->setPayment($this->pgInfo['cert_send_path'], [
            'MxID' => $this->pgInfo['mxid_billkey'],
            'MxIssueNO' => $orderList['no_order'],
            'MxIssueDate' => $orderList['ds_server_reg'],
            'CcProdDesc' => $orderList['nm_order'],
            'Amount' => '',
            'CcMode' => '10',
            'PayMethod' => 'CC',
            'TxCode' => 'EC131400',
            'RefundBankCode' => '',
            'HolderName' => '',
            'RefundAccount' => '',
            'FDHash' => md5($this->pgInfo['mxid_billkey'] . $orderList['no_order'] . $this->pgInfo['keydata_billkey']),
        ]);
        Log::channel('response')->info('fdk refund response: ', parameterReplace($response));

        return [
            'res_cd' => $response['ReplyCode'],
            'res_msg' => $response['ReplyMessage'],
        ];
    }

    private function rsaEncData(string $orgData, int|string $pubKey): string
    {
        openssl_public_encrypt($orgData, $encData, $pubKey, OPENSSL_PKCS1_OAEP_PADDING);
        return base64_encode($encData);
    }

    private function setPayment(string $path, array $parameter): array
    {
        Log::channel('request')->info('fdk request: ', $parameter);
        $method = match ($parameter['TxCode']) {
            'EC132000', 'EC131400' => 'paymentSendHttps',
            default => 'sendHttps'
        };
        return json_decode($this->fdk->$method($this->pgInfo['send_host'], $path, $parameter, '', 'U'), true);
    }
}
