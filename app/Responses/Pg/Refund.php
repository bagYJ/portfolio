<?php
declare(strict_types=1);

namespace App\Responses\Pg;

class Refund
{
    public readonly string $res_cd;
    public readonly string $res_msg;

    /**
     * @param array $response
     * @return $this
     */
    public function fdk(array $response): self
    {
        $this->res_cd = data_get($response, 'ReplyCode');
        $this->res_msg = data_get($response, 'ReplyMessage');

        return $this;
    }

    /**
     * @param string $code
     * @param string $message
     * @return $this
     */
    public function kcp(string $code, string $message): self
    {
        $this->res_cd = $code;
        $this->res_msg = $message;

        return $this;
    }

    /**
     * @param array $response
     * @return $this
     */
    public function nicepay(array $response): self
    {
        $this->res_cd = match (data_get($response, 'ResultCode')) {
            '2001', '2211' => getenv('RETURN_TRUE'),
            default => data_get($response, 'ResultCode')
        };
        $this->res_msg = data_get($response, 'ResultMsg');

        return $this;
    }

    /**
     * @param string $code
     * @param string $message
     * @return $this
     */
    public function uplus(string $code, string $message): self
    {
        $this->res_cd = $code;
        $this->res_msg = $message;

        return $this;
    }
}
