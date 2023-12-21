<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Pg\Fdk;
use App\Pg\Kcp;
use App\Pg\Nicepay;
use App\Pg\Uplus;
use App\Requests\Pg\Payment;
use App\Requests\Pg\Refund;
use App\Requests\Pg\Regist;
use Exception;

abstract class PgAbstract
{
    /**
     * @param Regist $request
     * @return array
     * @throws Exception
     */
    public static function regist(Regist $request): array
    {
        return [
            'kcp' => (new Kcp)->regist($request),
            'nicepay' => (new Nicepay)->regist($request),
            'uplus' => (new Uplus)->regist($request),
            'fdk' => (new Fdk)->regist($request),
        ];
    }

    /**
     * @param Payment $request
     * @return \App\Responses\Pg\Payment
     */
    public static function payment(Payment $request): \App\Responses\Pg\Payment
    {
        $pg = match ($request->nmPg) {
            'FDK' => new Fdk,
            'NICEPAY' => new Nicepay,
            'UPLUS' => new Uplus,
            'KCP', 'SUBSCRIPTION_KCP', 'INCARPAYMENT_KCP' => new Kcp
        };

        return $pg->payment($request);
    }

    /**
     * @param Refund $request
     * @return \App\Responses\Pg\Refund
     */
    public static function refund(Refund $request): \App\Responses\Pg\Refund
    {
        $pg = match ($request->nmPg) {
            'FDK' => new Fdk,
            'NICEPAY' => new Nicepay,
            'UPLUS' => new Uplus,
            'KCP', 'SUBSCRIPTION_KCP', 'INCARPAYMENT_KCP' => new Kcp
        };

        return $pg->refund($request);
    }

}
