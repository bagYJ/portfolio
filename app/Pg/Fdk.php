<?php
declare(strict_types=1);

namespace App\Pg;

use App\Requests\Pg\Payment;
use App\Requests\Pg\Refund;
use App\Requests\Pg\Regist;
use Exception;

class Fdk
{
    private readonly Fdk\Fdk $fdk;
    private readonly string $pubkey;

    public function __construct()
    {
        $this->fdk = new Fdk\Fdk();
        $this->pubkey = file_get_contents(sprintf('%s%s', storage_path(), getenv('FDK_PUBKEY')));
    }

    /**
     * @param Regist $request
     * @return \App\Responses\Pg\Regist
     * @throws Exception
     */
    public function regist(Regist $request): \App\Responses\Pg\Regist
    {
        $parameters = [
            'MxID' => getenv('FDK_BILLKEY'),
            'PayMethod' => 'CC',
            'CcMode' => '11',
            'SpecVer' => 'F101C000',
            'OrderID' => $request->noOrder,
            'EncodeType' => 'U',
            'CcNO' => $this->rsaEncData($request->cardNum, $this->pubkey),
            'CcExpDate' => substr(date('Y'), 0, 2) . $request->expYear . $request->expMon,
            'CcVfNO' => $this->rsaEncData(
                match (empty($request->noBiz)) {
                    true => substr($request->birthday, 2, 6),
                    false => $request->noBiz
                },
                $this->pubkey
            ),
            'CcVfValue' => $this->rsaEncData($request->noPin, $this->pubkey),
            'CcNameOnCard' => $request->nmBuyer,
            'PhoneNO' => '',
            'FDHash' => strtoupper(hash('sha256', sprintf('%s%s%s', getenv('FDK_MXID_BILLKEY'), $request->noOrder, getenv('FDK_KEYDATA_BILLKEY'))))
        ];

        $response = $this->setPayment(getenv('FDK_PATH_AUTH'), $parameters + [
                'MxIssueNO' => $request->noOrder,
                'TxCode' => 'EC139000'
            ]);
        if (data_get($response, 'ReplyCode') != getenv('RETURN_TRUE')) {
            $response = $this->setPayment(getenv('FDK_PATH_AUTH'), $parameters + [
                    'MxIssueNO' => '',
                    'TxCode' => 'EC139200'
                ]);
        }

        if (data_get($response, 'ReplyCode') != getenv('RETURN_TRUE')) {
            throw new Exception(data_get($response, 'ReplyMessage'));
        }

        return (new \App\Responses\Pg\Regist)->fdk($response);
    }

    /**
     * @param Payment $request
     * @return \App\Responses\Pg\Payment
     */
    public function payment(Payment $request): \App\Responses\Pg\Payment
    {
        $response = $this->setPayment(getenv('FDK_PATH_CERT'), [
            'MxID' => getenv('FDK_MXID_BILLKEY'),
            'MxIssueNO' => $request->noOrder,
            'MxIssueDate' => now()->format('YmdHis'),
            'PayMethod' => 'CC',
            'CcMode' => '10',
            'EncodeType' => 'U',
            'SpecVer' => 'F100C000',
            'TxCode' => 'EC132000',
            'Amount' => $request->price,
            'Currency' => 'KRW',
            'Tmode' => 'WEB',
            'Installment' => '00',
            'BillType' => '00',
            'CcNameOnCard' => $request->nmBuyer,
            'CcProdDesc' => sprintf('%s_%s', $request->noShop, $request->nmOrder),
            'PhoneNO' => $request->phone,
            'Email' => $request->email,
            'BillKey' => $request->billkey,
            'FDHash' => strtoupper(
                hash(
                    'sha256', sprintf('%s%s%s%s', getenv('FDK_MXID_BILLKEY'), $request->noOrder, $request->price, getenv('FDK_KEYDATA_BILLKEY'))
                )
            ),
        ]);

        return (new \App\Responses\Pg\Payment)->fdk($request, $response);
    }

    /**
     * @param Refund $request
     * @return \App\Responses\Pg\Refund
     */
    public function refund(Refund $request): \App\Responses\Pg\Refund
    {
        $response = $this->setPayment(getenv('FDK_PATH_CERT'), [
            'MxID' => getenv('FDK_MXID_BILLKEY'),
            'MxIssueNO' => $request->noOrder,
            'MxIssueDate' => $request->dsServerReg,
            'CcProdDesc' => $request->nmOrder,
            'Amount' => '',
            'CcMode' => '10',
            'PayMethod' => 'CC',
            'TxCode' => 'EC131400',
            'RefundBankCode' => '',
            'HolderName' => '',
            'RefundAccount' => '',
            'FDHash' => md5(sprintf('%s%s%s', getenv('FDK_MXID_BILLKEY'), $request->noOrder, getenv('FDK_KEYDATA_BILLKEY'))),
        ]);

        return (new \App\Responses\Pg\Refund)->fdk($response);
    }

    /**
     * @param string $orgData
     * @param int|string $pubKey
     * @return string
     */
    private function rsaEncData(string $orgData, int|string $pubKey): string
    {
        openssl_public_encrypt($orgData, $encData, $pubKey, OPENSSL_PKCS1_OAEP_PADDING);
        return base64_encode($encData);
    }

    /**
     * @param string $path
     * @param array $parameter
     * @return array
     */
    private function setPayment(string $path, array $parameter): array
    {
        $method = match (data_get($parameter, 'TxCode')) {
            'EC132000', 'EC131400' => 'paymentSendHttps',
            default => 'sendHttps'
        };
        return json_decode($this->fdk->$method(getenv('FDK_URI'), $path, $parameter, '', 'U'), true);
    }
}
