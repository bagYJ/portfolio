<?php

declare(strict_types=1);

namespace App\Services\Pg;

use App\Enums\EnumYN;
use App\Exceptions\OwinException;
use App\Interface\PgInterface;
use App\Services\Pg\Uplus\XPayClient;
use App\Utils\Code;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UplusService extends PgService implements PgInterface
{
    public XPayClient $xPayClient;

    public function __construct()
    {
        parent::__construct('uplus');
        $this->xPayClient = new XPayClient(app_path() . $this->pgInfo['home_dir'], $this->pgInfo['CST_PLATFORM']);
    }

    public function request(array $request): array
    {
        $noOrder = Auth::id() . (time() + 1000000000) . mt_rand(100, 999);

        $this->setPayment($this->pgInfo['LGD_MID'], [
            'LGD_TXNAME' => 'CardAuth',
            'LGD_OID' => $noOrder,
            'LGD_AMOUNT' => Code::conf('billkey.at_price_zero'),
            'LGD_PAN' => $request['no_cardnum'],
            'LGD_INSTALL' => '00',
            'LGD_BUYERPHONE' => Auth::user()->ds_phone,
            'LGD_PRODUCTINFO' => Code::conf('billkey.nm_order'),
            'LGD_BUYER' => Auth::user()->nm_user,
            'LGD_BUYERID' => Auth::user()->id_user,
            'LGD_BUYERIP' => getenv('REMOTE_ADDR'),
            'VBV_ECI' => '010',
            'LGD_BUYEREMAIL' => Auth::user()->id_user,
            'LGD_ENCODING' => $this->pgInfo['encode'],
            'LGD_ENCODING_NOTEURL' => $this->pgInfo['encode'],
            'LGD_ENCODING_RETURNURL' => $this->pgInfo['encode'],
            'LGD_EXPYEAR' => $request['no_expyea'],
            'LGD_EXPMON' => $request['no_expmon'],
            'LGD_PIN' => $request['no_pin'],
            'LGD_PRIVATENO' => match (empty($request['no_biz'])) {
                true => substr(Auth::user()->ds_birthday, 2, 6),
                false => $request['no_biz']
            }
        ]);

        $cardResult = $this->hasBillkey(
            Auth::id(),
            $this->xPayClient->Response('LGD_BILLKEY'),
            EnumYN::N,
            $this->pgInfo['code']
        );
        if ($cardResult === true) {
            throw new OwinException(Code::message('P1023'));
        }
        Log::channel('response')->info('uplus request response: ', parameterReplace($this->xPayClient->response_array));

        return [
            'result_msg' => $this->xPayClient->Response_Msg(), //
            'result_code' => $this->xPayClient->Response_Code(), //
            'ds_billkey' => $this->xPayClient->Response('LGD_BILLKEY'), //
            'cd_pg' => $this->pgInfo['code'], //
            'cd_card_corp' => substr($this->xPayClient->Response('LGD_FINANCECODE'), 0, 2), //
            'no_card_user' => substr($this->xPayClient->Response('LGD_CARDNUM'), -4), //
            'res_param' => '', //
            'ds_res_order_no' => $noOrder ?? '', //
            'yn_credit' => EnumYN::N->name, //
        ];
    }

    public function payment(array $request): array
    {
        $this->setPayment($this->pgInfo['LGD_MID'], [
            'LGD_TXNAME' => 'CardAuth',
            'LGD_OID' => $request['no_order'],
            'LGD_AMOUNT' => $request['at_price_pg'],
            'LGD_PAN' => $request['ds_billkey'],
            'LGD_INSTALL' => '00',
            'LGD_BUYERPHONE' => Auth::user()->ds_phone,
            'LGD_PRODUCTINFO' => $request['nm_order'],
            'LGD_BUYER' => Auth::user()->nm_user,
            'LGD_BUYERID' => Auth::user()->id_user,
            'LGD_BUYERIP' => getenv('REMOTE_ADDR'),
            'VBV_ECI' => '010',
            'LGD_BUYEREMAIL' => Auth::user()->id_user,
            'LGD_ENCODING' => $this->pgInfo['encode'],
            'LGD_ENCODING_NOTEURL' => $this->pgInfo['encode'],
            'LGD_ENCODING_RETURNURL' => $this->pgInfo['encode'],
            'LGD_EXPYEAR' => '',
            'LGD_EXPMON' => '',
            'LGD_PIN' => '',
            'LGD_PRIVATENO' => ''
        ]);
        Log::channel('response')->info('uplus payment response: ', parameterReplace($this->xPayClient->response_array));

        return [
            'res_cd' => $this->xPayClient->Response_Code(),
            'res_msg' => $this->xPayClient->Response_Msg(),
            'ds_res_order_no' => $this->xPayClient->Response('LGD_TID'),
            'at_price_pg' => $this->xPayClient->Response('LGD_AMOUNT'),
            'ds_req_param' => $request,
            'ds_res_param' => $this->xPayClient->response_json
        ];
    }

    public function refund(array $orderList, string $reason): array
    {
        $this->setPayment($this->pgInfo['LGD_MID'], [
            'LGD_TXNAME' => 'Cancel',
            'LGD_TID' => $orderList['ds_res_order_no']
        ]);

        Log::channel('response')->info('uplus refund response: ', parameterReplace($this->xPayClient->response_array));

        return [
            'res_cd' => $this->xPayClient->Response_Code(),
            'res_msg' => $this->xPayClient->Response_Msg(),
        ];
    }

    private function setPayment(string $mid, array $parameter): void
    {
        $this->xPayClient->Init_TX($mid);
        foreach ($parameter as $key => $value) {
            $this->xPayClient->Set($key, $value);
        }
        $this->xPayClient->TX();
    }
}
