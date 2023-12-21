<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Models\OrderList;
use App\Models\ParkingOrderList;
use App\Utils\Common;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;

abstract class PushAbstract
{
    /**
     * @param OrderList|ParkingOrderList|Model $order
     * @param string $path
     * @param string $bizKind
     * @param string $step
     * @param string $ordering
     * @return void
     * @throws GuzzleException
     */
    public static function send(OrderList|ParkingOrderList|Model $order, string $path, string $bizKind, string $step, string $ordering = 'Y'): void
    {
        Common::client(Method::POST, $path, [
            'form_params' => [
                'no_users' => [$order->no_user],
                'title' => sprintf(env(sprintf('FCM_USER_%s_%s_TITLE', strtoupper($bizKind), strtoupper($step))), sprintf('%s %s', $order?->partner?->nm_partner, $order->shop->nm_shop)),
                'body' => match ($step) {
                    'shop_accept', 'shop_accept_shop', 'shop_accept_car' => sprintf(env(sprintf('FCM_USER_%s_%s_BODY', strtoupper($bizKind), strtoupper($step))), sprintf('%s %s', match ($order->dt_pickup->format('a')) {
                        'am' => '오전',
                        'pm' => '오후'
                    }, $order->dt_pickup->format('h:i'))),
                    'complete' => sprintf(env(sprintf('FCM_USER_%s_%s_BODY', strtoupper($bizKind), strtoupper($step))), $order->at_price_pg),
                    default => env(sprintf('FCM_USER_%s_%s_BODY', strtoupper($bizKind), strtoupper($step)))
                },
                'status' => match ($step) {
                    'shop_accept', 'shop_accept_shop', 'shop_accept_car' => '매장수락',
                    'shop_complete', 'shop_complete_shop', 'shop_complete_car' => '준비완료',
                    'delivery_complete', 'delivery_complete_shop', 'delivery_complete_car' => '전달완료',
                    'cancel', 'cancel_car', 'cancel_shop', 'cancel_etc', 'cancel_etc_car', 'cancel_etc_shop' => '매장취소',
                    'enter' => '입차완료',
                    'complete' => '출차완료',
                    'expire' => '주문취소',
                },
                'biz_kind' => $bizKind,
                'no_shop' => $order->no_shop,
                'no_order' => $order->no_order,
                'is_ordering' => $ordering
            ]
        ]);
    }
}
