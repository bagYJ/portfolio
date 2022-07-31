<?php

declare(strict_types=1);

namespace App\Services\Pg;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Interface\PgInterface;
use App\Services\Pg\Nicepay\NicepayLite;
use App\Utils\Code;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function storage_path;

class NicepayService extends PgService implements PgInterface
{
    private NicepayLite $nicepayLite;

    public function __construct()
    {
        parent::__construct('nicepay');
        $this->nicepayLite = new NicepayLite();
    }

    public function request(array $request): array
    {
        $this->setPayment([
            'm_LicenseKey' => $this->pgInfo['license_key'],
            'm_MID' => $this->pgInfo['mid'],
            'm_NicepayHome' => storage_path('logs/nicepay'),
            'm_PayMethod' => $this->pgInfo['pay_method']['card'],
            'm_ssl' => $this->pgInfo['m_ssl'],
            'm_ActionType' => $this->pgInfo['action_type']['buy'],
            'm_CardNo' => $request['no_cardnum'],
            'm_ExpYear' => $request['no_expyea'],
            'm_ExpMonth' => $request['no_expmon'],
            'm_CardPw' => $request['no_pin'],
            'm_charSet' => $this->pgInfo['encode'],
            'm_IDNo' => match (empty($request['no_biz'])) {
                true => substr(Auth::user()->ds_birthday, 2, 6),
                false => $request['no_biz']
            }
        ]);
        if ($this->nicepayLite->m_ResultData['ResultCode'] != 'F100') {
            throw new OwinException($this->nicepayLite->m_ResultData['ResultMsg']);
        }

        $result = [
            'response_code' => match ($this->nicepayLite->m_ResultData['ResultCode'] == 'F100') {
                true => '0000',
                default => $this->nicepayLite->m_ResultData['ResultCode']
            },
            'rtncardno' => substr($this->nicepayLite->m_ResultData['CardNo'], -4), // 카드번호뒷자리4
            'rtnisucd' => substr($this->nicepayLite->m_ResultData['CardCode'], 0, 2), // 카드사구분코드
            'ds_billkey' => $this->nicepayLite->m_ResultData['BID'],
            'ds_res_order_no' => '',
            'ds_credit' => $this->nicepayLite->m_ResultData['CardCl'],
        ];

        $cardResult = $this->hasBillkey(Auth::id(), $result['ds_billkey'], EnumYN::N, $this->pgInfo['code']);
        if ($cardResult === true) {
            throw new OwinException(Code::message('P1023'));
        }

        $this->setMemberCardRequest(
            noUser: Auth::id(),
            dsFdkHash: ['response_code' => $result['response_code'], 'ds_res_order_no' => $result['ds_res_order_no']],
            dsOwinHash: [
                'CD_PG' => $this->pgInfo['code'],
                'card_expiry' => $request['no_expyea'] . $request['no_expmon']
            ],
            cdCardRegist: $result['response_code'],
            dsResParam: $this->nicepayLite->m_ResultData
        );
        Log::channel('response')->info('nicepay request response: ', parameterReplace($result));

//        $cdCardCorp = '5030' . Code::card(sprintf('%s.%s', $this->pgInfo['code'], $result['rtnisucd']));
//        if (in_array($cdCardCorp, array_keys(Code::conf('unable_card')))) throw new OwinException(sprintf(Code::message('P1029'), Code::conf('unable_card.' . $cdCardCorp)));

        return [
            'result_msg' => $this->nicepayLite->m_ResultData['ResultMsg'],
            'result_code' => $this->nicepayLite->m_ResultData['ResultCode'] == 'F100' ? '0000' : $this->nicepayLite->m_ResultData['ResultCode'],
            'ds_billkey' => $result['ds_billkey'],
            'cd_pg' => $this->pgInfo['code'],
            'cd_card_corp' => $result['rtnisucd'],
            'no_card_user' => $result['rtncardno'],
            'res_param' => $this->nicepayLite->m_ResultData,
            'ds_res_order_no' => $result['ds_res_order_no'],
            'yn_credit' => $result['ds_credit'] > 0 ? EnumYN::Y->name : EnumYN::N->name,
        ];
    }

    public function payment(array $request): array
    {
        $this->setPayment([
            'm_LicenseKey' => $this->pgInfo['license_key'],
            'm_MID' => $this->pgInfo['mid'],
            'm_NicepayHome' => storage_path('logs/nicepay'),
            'm_PayMethod' => $this->pgInfo['pay_method']['order'],
            'm_ssl' => $this->pgInfo['m_ssl'],
            'm_ActionType' => $this->pgInfo['action_type']['buy'],
            'm_NetCancelPW' => $this->pgInfo['cancel_pwd'],
            'm_Amt' => $request['at_price_pg'],
            'm_NetCancelAmt' => $request['at_price_pg'],
            'm_Moid' => $request['no_order'],
            'm_BillKey' => $request['ds_billkey'],
            'm_BuyerName' => iconv('UTF-8', 'EUC-KR', $request['nm_user']),
            'm_GoodsName' => iconv('UTF-8', 'EUC-KR', $request['nm_order']),
            'm_CardQuota' => '00',
            'm_charSet' => $this->pgInfo['encode']
        ]);
        Log::channel('response')->info(
            'nicepay payment response: ',
            parameterReplace($this->nicepayLite->m_ResultData)
        );

        return [
            'res_cd' => match ($this->nicepayLite->m_ResultData['ResultCode']) {
                '3001' => '0000',
                default => $this->nicepayLite->m_ResultData['ResultCode']
            },
            'res_msg' => $this->nicepayLite->m_ResultData['ResultMsg'],
            'ds_res_order_no' => $this->nicepayLite->m_ResultData['TID'],
            'at_price_pg' => $this->nicepayLite->m_ResultData['Amt'],
            'ds_req_param' => $request,
            'ds_res_param' => json_encode($this->nicepayLite->m_ResultData, JSON_UNESCAPED_UNICODE)
        ];
    }

    public function refund(array $orderList, string $reason): array
    {
        $this->setPayment([
            'm_LicenseKey' => $this->pgInfo['license_key'],
            'm_MID' => $this->pgInfo['mid'],
            'm_NicepayHome' => storage_path('logs/nicepay'),
            'm_ssl' => $this->pgInfo['m_ssl'],
            'm_ActionType' => $this->pgInfo['action_type']['cancel'],
            'm_CancelPwd' => $this->pgInfo['cancel_pwd'],
            'm_TID' => $orderList['ds_res_order_no'],
            'm_CancelAmt' => $orderList['at_price_pg'],
            'm_CancelMsg' => $reason,
            'm_charSet' => $this->pgInfo['encode']
        ]);
        Log::channel('response')->info('nicepay refund response: ', parameterReplace($this->nicepayLite->m_ResultData));

        return [
            'res_cd' => match ($this->nicepayLite->m_ResultData['ResultCode']) {
                '2001', '2211' => '0000',
                default => $this->nicepayLite->m_ResultData['ResultCode']
            },
            'res_msg' => $this->nicepayLite->m_ResultData['ResultMsg'],
        ];
    }

    private function setPayment(array $parameter): void
    {
        foreach ($parameter as $key => $value) {
            $this->nicepayLite->$key = $value;
        }
        $this->nicepayLite->startAction();
    }
}
