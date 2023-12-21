<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Models\OrderList;
use App\Utils\Common;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class RkmAbstract
{
    /**
     * @param Method $method
     * @param string $path
     * @param array $options
     * @return void
     * @throws Exception|GuzzleException
     */
    private static function client(Method $method, string $path, array $options): void
    {
        $response = Common::client($method, sprintf('%s%s', getenv('RKM_URI'), $path), $options);
        Log::channel('client')->info(sprintf('%s RESPONSE ', $path), [$response->getBody()->getContents()]);
    }

    /**
     * @param OrderList|Model $order
     * @param string $bizKind
     * @param string $step
     * @return void
     * @throws GuzzleException
     */
    public static function push(OrderList|Model $order, string $bizKind, string $step): void
    {
        self::client(Method::POST, sprintf('%s%s', env('RKM_URI'), env('RKM_PATH_PUSH')), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'AppPushKey' => env('RKM_KEY')
            ],
            'form_params' => [
                'targetVin' => $order->member->detail->ds_access_vin_rsm,
                'pTitle' => sprintf(env(sprintf('FCM_USER_%s_%s_TITLE', strtoupper($bizKind), strtoupper($step))), sprintf('%s %s', $order?->partner?->nm_partner, $order->shop->nm_shop)),
                'pBody' => match ($step) {
                    'shop_accept', 'shop_accept_shop', 'shop_accept_car' => sprintf(
                        env(sprintf('FCM_USER_%s_%s_BODY', strtoupper($bizKind), strtoupper($step))), sprintf(
                        '%s %s', match ($order->dt_pickup->format('a')) {
                        'am' => '오전',
                        'pm' => '오후'
                    }, $order->dt_pickup->format('h:i'))),
                    'complete' => sprintf(env(sprintf('FCM_USER_%s_%s_BODY', strtoupper($bizKind), strtoupper($step))), $order->at_price_pg),
                    default => env(sprintf('FCM_USER_%s_%s_BODY', strtoupper($bizKind), strtoupper($step))),
                },
                'type' => 'I'
            ]
        ]);
    }
}