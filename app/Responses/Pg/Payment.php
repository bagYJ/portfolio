<?php
declare(strict_types=1);

namespace App\Responses\Pg;

class Payment
{
    public readonly string $res_cd;
    public readonly string $res_msg;
    public readonly ?string $ds_res_order_no;
    public readonly int $at_price_pg;
    public readonly string $ds_req_param;
    public readonly string $ds_res_param;

    /**
     * @param \App\Requests\Pg\Payment $request
     * @param array $response
     * @return $this
     */
    public function fdk(\App\Requests\Pg\Payment $request, array $response): self
    {
        $this->res_cd = data_get($response, 'ReplyCode');
        $this->res_msg = data_get($response, 'ReplyMessage');
        $this->ds_res_order_no = data_get($response, 'AuthNO');
        $this->at_price_pg = (int)data_get($response, 'Amount');
        $this->ds_req_param = json_encode($request, JSON_UNESCAPED_UNICODE);
        $this->ds_res_param = json_encode($response, JSON_UNESCAPED_UNICODE);

        return $this;
    }

    /**
     * @param \App\Requests\Pg\Payment $request
     * @param string $code
     * @param string $message
     * @param array $response
     * @return $this
     */
    public function kcp(\App\Requests\Pg\Payment $request,  string $code, string $message, array $response): self
    {
        $this->res_cd = $code;
        $this->res_msg = $message;
        $this->ds_res_order_no = data_get($response, 'tno');
        $this->at_price_pg = (int)data_get($response, 'card_mny');
        $this->ds_req_param = json_encode($request, JSON_UNESCAPED_UNICODE);
        $this->ds_res_param = json_encode($response, JSON_UNESCAPED_UNICODE);

        return $this;
    }

    /**
     * @param \App\Requests\Pg\Payment $request
     * @param array $response
     * @return $this
     */
    public function nicepay(\App\Requests\Pg\Payment $request, array $response): self
    {
        $this->res_cd = match (data_get($response, 'ResultCode')) {
            '3001' => getenv('RETURN_TRUE'),
            default => data_get($response, 'ResultCode')
        };
        $this->res_msg = data_get($response, 'ResultMsg');
        $this->ds_res_order_no = data_get($response, 'TID');
        $this->at_price_pg = (int)data_get($response, 'Amt');
        $this->ds_req_param = json_encode($request, JSON_UNESCAPED_UNICODE);
        $this->ds_res_param = json_encode($response, JSON_UNESCAPED_UNICODE);

        return $this;
    }

    /**
     * @param \App\Requests\Pg\Payment $request
     * @param string $code
     * @param string $message
     * @param array $response
     * @return $this
     */
    public function uplus(\App\Requests\Pg\Payment $request, string $code, string $message, array $response): self
    {
        $this->res_cd = $code;
        $this->res_msg = $message;
        $this->ds_res_order_no = data_get($response, 'LGD_TID');
        $this->at_price_pg = (int)data_get($response, 'LGD_AMOUNT');
        $this->ds_req_param = json_encode($request, JSON_UNESCAPED_UNICODE);
        $this->ds_res_param = json_encode($response, JSON_UNESCAPED_UNICODE);

        return $this;
    }
}
