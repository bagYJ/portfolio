<?php
declare(strict_types=1);

namespace App\Pg;

use App\Pg\Nicepay\NicepayLite;
use App\Requests\Pg\Payment;
use App\Requests\Pg\Refund;
use App\Requests\Pg\Regist;
use Exception;

class Nicepay
{
    private readonly NicepayLite $nicepayLite;

    public function __construct()
    {
        $this->nicepayLite = new NicepayLite();
    }

    /**
     * @param Regist $request
     * @return \App\Responses\Pg\Regist
     * @throws Exception
     */
    public function regist(Regist $request): \App\Responses\Pg\Regist
    {
        $this->setPayment([
            'm_LicenseKey' => getenv('NICEPAY_LICENSE_KEY'),
            'm_MID' => getenv('NICEPAY_MID'),
            'm_NicepayHome' => storage_path('logs/nicepay'),
            'm_PayMethod' => getenv('NICEPAY_PAY_METHOD_CARD'),
            'm_ssl' => getenv('NICEPAY_M_SSL'),
            'm_ActionType' => getenv('NICEPAY_ACTION_TYPE_BUY'),
            'm_CardNo' => $request->cardNum,
            'm_ExpYear' => $request->expYear,
            'm_ExpMonth' => $request->expMon,
            'm_CardPw' => $request->noPin,
            'm_charSet' => getenv('NICEPAY_ENCODE'),
            'm_IDNo' => match (empty($request->noBiz)) {
                true => substr($request->birthday, 2, 6),
                false => $request->noBiz
            }
        ]);
        if (data_get($this->nicepayLite->m_ResultData, 'ResultCode') != 'F100') {
            throw new Exception(data_get($this->nicepayLite->m_ResultData, 'ResultMsg'));
        }

        return (new \App\Responses\Pg\Regist)->nicepay($this->nicepayLite->m_ResultData);
    }

    /**
     * @param Payment $request
     * @return \App\Responses\Pg\Payment
     */
    public function payment(Payment $request): \App\Responses\Pg\Payment
    {
        $this->setPayment([
            'm_LicenseKey' => getenv('NICEPAY_LICENSE_KEY'),
            'm_MID' => getenv('NICEPAY_MID'),
            'm_NicepayHome' => storage_path('logs/nicepay'),
            'm_PayMethod' => getenv('NICEPAY_PAY_METHOD_ORDER'),
            'm_ssl' => getenv('NICEPAY_M_SSL'),
            'm_ActionType' => getenv('NICEPAY_ACTION_TYPE_BUY'),
            'm_NetCancelPW' => getenv('NICEPAY_CANCEL_PWD'),
            'm_Amt' => $request->price,
            'm_NetCancelAmt' => $request->price,
            'm_Moid' => $request['no_order'],
            'm_BillKey' => $request->billkey,
            'm_BuyerName' => iconv('UTF-8', 'CP949', $request->nmBuyer),
            'm_GoodsName' => iconv('UTF-8', 'CP949', $request->nmOrder),
            'm_CardQuota' => '00',
            'm_charSet' => getenv('NICEPAY_ENCODE')
        ]);

        return (new \App\Responses\Pg\Payment)->nicepay($request, $this->nicepayLite->m_ResultData);
    }

    public function refund(Refund $request): \App\Responses\Pg\Refund
    {
        $this->setPayment([
            'm_LicenseKey' => getenv('NICEPAY_LICENSE_KEY'),
            'm_MID' => getenv('NICEPAY_MID'),
            'm_NicepayHome' => storage_path('logs/nicepay'),
            'm_ssl' => getenv('NICEPAY_M_SSL'),
            'm_ActionType' => getenv('NICEPAY_ACTION_TYPE_CANCEL'),
            'm_CancelPwd' => getenv('NICEPAY_CANCEL_PWD'),
            'm_TID' => $request->dsResOrderNo,
            'm_CancelAmt' => $request->price,
            'm_CancelMsg' => $request->reason,
            'm_charSet' => getenv('NICEPAY_ENCODE')
        ]);

        return (new \App\Responses\Pg\Refund)->nicepay($this->nicepayLite->m_ResultData);
    }

    /**
     * @param array $parameters
     * @return void
     */
    private function setPayment(array $parameters): void
    {
        foreach ($parameters as $key => $value) {
            $this->nicepayLite->$key = $value;
        }
        $this->nicepayLite->startAction();
    }
}
