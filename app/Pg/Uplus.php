<?php
declare(strict_types=1);

namespace App\Pg;

use App\Pg\Uplus\XPayClient;
use App\Requests\Pg\Payment;
use App\Requests\Pg\Refund;
use App\Requests\Pg\Regist;
use Exception;

class Uplus
{
    private readonly XPayClient $client;

    public function __construct()
    {
        $this->client = new XPayClient(sprintf('%s%s', app_path(), getenv('UPLUS_HOME_DIR')), getenv('UPLUS_CST_PLATFORM'));
    }

    /**
     * @param Regist $request
     * @return \App\Responses\Pg\Regist
     * @throws Exception
     */
    public function regist(Regist $request): \App\Responses\Pg\Regist
    {
        $this->setPayment(getenv('UPLUS_LGD_MID'), [
            'LGD_TXNAME' => 'CardAuth',
            'LGD_OID' => $request->noOrder,
            'LGD_AMOUNT' => $request->price,
            'LGD_PAN' => $request->cardNum,
            'LGD_INSTALL' => '00',
            'LGD_BUYERPHONE' => $request->phone,
            'LGD_PRODUCTINFO' => $request->nmOrder,
            'LGD_BUYER' => $request->nmBuyer,
            'LGD_BUYERID' => $request->email,
            'LGD_BUYERIP' => getenv('REMOTE_ADDR'),
            'VBV_ECI' => '010',
            'LGD_BUYEREMAIL' => $request->email,
            'LGD_ENCODING' => getenv('UPLUS_ENCODE'),
            'LGD_ENCODING_NOTEURL' => getenv('UPLUS_ENCODE'),
            'LGD_ENCODING_RETURNURL' => getenv('UPLUS_ENCODE'),
            'LGD_EXPYEAR' => $request->expYear,
            'LGD_EXPMON' => $request->expMon,
            'LGD_PIN' => $request->noPin,
            'LGD_PRIVATENO' => match (empty($request->noBiz)) {
                true => substr($request->birthday, 2, 6),
                false => $request->noBiz
            }
        ]);

        if ($this->client->Response_Code() != getenv('RETURN_TRUE')) {
            throw new Exception($this->client->Response_Msg());
        }

        return (new \App\Responses\Pg\Regist)->uplus($this->client->Response_Code(), $this->client->Response_Msg(), $this->client->response_array['LGD_RESPONSE'][0]);
    }

    /**
     * @param Payment $request
     * @return \App\Responses\Pg\Payment
     */
    public function payment(Payment $request): \App\Responses\Pg\Payment
    {
        $this->setPayment(getenv('UPLUS_LGD_MID'), [
            'LGD_TXNAME' => 'CardAuth',
            'LGD_OID' => $request->noOrder,
            'LGD_AMOUNT' => $request->price,
            'LGD_PAN' => $request->billkey,
            'LGD_INSTALL' => '00',
            'LGD_BUYERPHONE' => $request->phone,
            'LGD_PRODUCTINFO' => $request->nmOrder,
            'LGD_BUYER' => $request->nmBuyer,
            'LGD_BUYERID' => $request->email,
            'LGD_BUYERIP' => getenv('REMOTE_ADDR'),
            'VBV_ECI' => '010',
            'LGD_BUYEREMAIL' => $request->email,
            'LGD_ENCODING' => getenv('UPLUS_ENCODE'),
            'LGD_ENCODING_NOTEURL' => getenv('UPLUS_ENCODE'),
            'LGD_ENCODING_RETURNURL' => getenv('UPLUS_ENCODE'),
            'LGD_EXPYEAR' => '',
            'LGD_EXPMON' => '',
            'LGD_PIN' => '',
            'LGD_PRIVATENO' => ''
        ]);

        return (new \App\Responses\Pg\Payment)->uplus($request, $this->client->Response_Code(), $this->client->Response_Msg(), $this->client->response_array[0]);
    }

    public function refund(Refund $request): \App\Responses\Pg\Refund
    {
        $this->setPayment(getenv('UPLUS_LGD_MID'), [
            'LGD_TXNAME' => 'Cancel',
            'LGD_TID' => $request->dsResOrderNo
        ]);

        return (new \App\Responses\Pg\Refund)->uplus($this->client->Response_Code(), $this->client->Response_Msg());
    }

    /**
     * @param string $mid
     * @param array $parameters
     * @return void
     */
    private function setPayment(string $mid, array $parameters): void
    {
        $this->client->Init_TX($mid);
        foreach ($parameters as $key => $value) {
            $this->client->Set($key, $value);
        }
        $this->client->TX();
    }
}
