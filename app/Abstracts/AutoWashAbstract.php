<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Models\OrderList;
use App\Utils\Common;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class AutoWashAbstract
{
    /**
     * @param Method $method
     * @param string $path
     * @param array|null $options
     * @return array
     * @throws AuthorizationException
     * @throws Exception
     */
    public static function client(Method $method, string $path, ?array $options = []): array
    {
        try {
            $response = Common::client($method, sprintf('%s%s', getenv('OWIN_URI'), $path), $options);
            $content = json_decode($response->getBody()->getContents(), true);
            Log::channel('client')->info(sprintf('%s %s RESPONSE ', sprintf('%s%s', getenv('OWIN_URI'), $path), $method->name), $content);

            return match ($response->getStatusCode()) {
                200 => match (data_get($content, 'result')) {
                    true => $content,
                    default => throw new Exception(data_get($content, 'message'))
                },
                400 => throw new BadRequestHttpException(message: data_get($content, 'message')),
                401 => throw new AuthorizationException(message: data_get($content, 'message')),
                default => throw new Exception(data_get($content, 'message'))
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
     * @param int $noShop
     * @return array
     * @throws AuthorizationException
     */
    public static function info(int $noShop): array
    {
        return self::client(Method::GET, sprintf(getenv('OWIN_AUTO_WASH_PATH_INFO'), $noShop));
    }

    /**
     * @param int $noShop
     * @return array
     * @throws AuthorizationException
     */
    public static function intro(int $noShop): array
    {
        return self::client(Method::GET, getenv('OWIN_AUTO_WASH_PATH_INTRO'), [
            'parameters' => [
                'no_shop' => $noShop
            ]
        ]);
    }

    /**
     * @param string $noOrder
     * @return OrderList
     */
    public static function detail(string $noOrder): OrderList
    {
        return OrderList::getOrderInfo($noOrder)->load(['shop.partner']);
    }

    public static function payment(): array
    {

    }

    public static function complete(string $noOrder): array
    {

    }
}
