<?php
declare(strict_types=1);

namespace App\Responses\Pg;

class Regist
{
    public readonly string $result_msg;
    public readonly string $result_code;
    public readonly string $ds_billkey;
    public readonly string $cd_pg;
    public readonly string $cd_card_corp;
    public readonly string $no_card_user;
    public readonly array $res_param;
    public readonly ?string $ds_res_order_no;
    public readonly string $yn_credit;

    /**
     * @param array $response
     * @return $this
     */
    public function fdk(array $response): self
    {
        $this->result_msg = data_get($response, 'ReplyMessage');
        $this->result_code = data_get($response, 'ReplyCode');
        $this->ds_billkey = data_get($response, 'BillKey');
        $this->cd_pg = getenv('FDK_CODE');
        $this->cd_card_corp = data_get($response, 'IssCD');
        $this->no_card_user = data_get($response, 'CcNO');
        $this->res_param = $response;
        $this->ds_res_order_no = data_get($response, 'AuthNO');
        $this->yn_credit = data_get($response, 'CheckYn');

        return $this;
    }

    /**
     * @param string $cardNum
     * @param string $noOrder
     * @param array $response
     * @return $this
     */
    public function kcp(string $cardNum, string $noOrder, array $response): self
    {
        $this->result_msg = data_get($response, 'res_msg');
        $this->result_code = data_get($response, 'res_cd');
        $this->ds_billkey = data_get($response, 'batch_key');
        $this->cd_pg = getenv('KCP_CODE');
        $this->cd_card_corp = sprintf('%s%s', data_get($response, 'card_cd'), (data_get($response, 'card_cd') == 'CCBC' ? data_get($response, 'card_bank_cd') : ''));
        $this->no_card_user = substr($cardNum, 12, 4);
        $this->res_param = $response;
        $this->ds_res_order_no = $noOrder;
        $this->yn_credit = 'N';

        return $this;
    }

    /**
     * @param array $response
     * @return $this
     */
    public function nicepay(array $response): self
    {
        $this->result_msg = data_get($response, 'ResultMsg');
        $this->result_code = match (data_get($response, 'ResultCode')) {
            'F100' => getenv('RETURN_TRUE'),
            default => data_get($response, 'ResultCode')
        };
        $this->ds_billkey = data_get($response, 'BID');
        $this->cd_pg = getenv('NICEPAY_CODE');
        $this->cd_card_corp = substr(data_get($response, 'CardCode'), 0, 2);
        $this->no_card_user = substr(data_get($response, 'CardNo'), -4);
        $this->res_param = $response;
        $this->ds_res_order_no = null;
        $this->yn_credit = match (data_get($response, 'CardCl') > 0) {
            true => 'Y',
            default => 'N'
        };

        return $this;
    }

    /**
     * @param string $code
     * @param string $message
     * @param array $response
     * @return $this
     */
    public function uplus(string $code, string $message, array $response): self
    {
        $this->result_msg = $message;
        $this->result_code = $code;
        $this->ds_billkey = data_get($response, 'LGD_BILLKEY');
        $this->cd_pg = getenv('UPLUS_CODE');
        $this->cd_card_corp = substr(data_get($response, 'LGD_FINANCECODE'), 0, 2);
        $this->no_card_user = substr(data_get($response, 'LGD_CARDNUM'), -4);
        $this->res_param = $response;
        $this->ds_res_order_no = null;
        $this->yn_credit = 'N';

        return $this;
    }
}
