<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Requests\Parking\Order;
use App\Requests\Parking\OrderList;
use App\Requests\Parking\OrderSearch;
use App\Utils\Common;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class ParkingAbstract
{
    /**
     * @param Method $method
     * @param string $path
     * @param array|null $options
     * @return array
     * @throws AuthorizationException|Exception
     */
    private static function client(Method $method, string $path, ?array $options = []): array
    {
        try {
            $response = Common::client($method, sprintf('%s%s', getenv('PARKING_URI'), $path), $options + self::getHeaders());
            $content = json_decode($response->getBody()->getContents(), true);
            Log::channel('client')->info(sprintf('%s %s RESPONSE ', sprintf('%s%s', getenv('PARKING_URI'), $path), $method->name), $content);

            return match ($response->getStatusCode()) {
                200 => $content,
                400 => throw new BadRequestHttpException(message: data_get($content, 'message'), code: data_get($content, 'code')),
                401 => throw new AuthorizationException(message: data_get($content, 'message'), code: data_get($content, 'code')),
                default => throw new Exception(data_get($content, 'message'), data_get($content, 'code'))
            };
        } catch (BadRequestHttpException $e) {
            throw new BadRequestHttpException(message: $e->getMessage(), code: $e->getCode());
        } catch (AuthorizationException $e) {
            throw new AuthorizationException(message: $e->getMessage(), code: $e->getCode());
        } catch (Throwable $t) {
            throw new Exception($t->getMessage(), $t->getCode());
        }
    }

    /**
     * @return array[]
     */
    #[ArrayShape(['headers' => 'array'])]
    private static function getHeaders(): array
    {
        return [
            'headers' => [
                getenv('PARKING_HEADER_KEY') => getenv('PARKING_HEADER_VALUE')
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public static function order(Order $request): array
    {
        return self::client(Method::POST, getenv('PARKING_PATH_BOOKINGS'), [
            'json' => $request
        ]);
    }

    /**
     * @param OrderList $request
     * @return array
     * @throws Exception
     */
    public static function orderList(OrderList $request): array
    {
        return self::client(Method::GET, getenv('PARKING_PATH_BOOKINGS'), [
            'json' => $request
        ]);
    }

    /**
     * @param OrderSearch $request
     * @return array
     * @throws Exception
     */
    public static function orderSearch(OrderSearch $request): array
    {
        return self::client(Method::POST, getenv('PARKING_PATH_BOOKINGS_SEARCH'), [
            'json' => $request
        ]);
    }

    /**
     * @param string $bookingUid
     * @return array
     * @throws AuthorizationException
     */
    public static function orderDetail(string $bookingUid): array
    {
        return self::client(Method::GET, sprintf(getenv('PARKING_PATH_BOOKINGS_DETAIL'), $bookingUid));
    }

    /**
     * @param int $bookingUid
     * @return array
     * @throws AuthorizationException
     */
    public static function cancel(int $bookingUid): array
    {
        return self::client(Method::PUT, sprintf(getenv('PARKING_PATH_BOOKINGS_CANCEL'), $bookingUid));
    }

    /**
     * @param string $bookingUid
     * @return array
     * @throws AuthorizationException
     */
    public static function used(string $bookingUid): array
    {
        return self::client(Method::PUT, sprintf(getenv('PARKING_PATH_BOOKINGS_USED'), $bookingUid));
    }

    /**
     * @param string $siteUid
     * @return array
     * @throws AuthorizationException
     */
    public static function site(string $siteUid): array
    {
        return self::client(Method::GET, sprintf(getenv('PARKING_PATH_SITE'), $siteUid));
    }
}
