<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Requests\Bizcall\AutoMapping;
use App\Utils\Common;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

abstract class BizcallAbstract
{
    abstract public static function autoMapping(AutoMapping $mapping): array;
    abstract public static function closeMapping(string $virtualNumber): array;

    /**
     * @param Method $method
     * @param string $path
     * @param array $options
     * @return array
     * @throws GuzzleException
     */
    public static function client(Method $method, string $path, array $options): array
    {
        $response = Common::client($method, sprintf('%s%s', getenv('BIZCALL_URI'), $path), $options);
        $content = json_decode($response->getBody()->getContents(), true);
        Log::channel('client')->info(sprintf('%s %s RESPONSE ', sprintf('%s%s', getenv('BIZCALL_URI'), $path), $method->name), $content);

        return $content;
    }

    /**
     * @throws Exception
     */
    public static function getVns(): array
    {
        try {
            $response = self::client(Method::POST, getenv('BIZCALL_PATH_LIST'), [
                'form_params' => [
                    'iid' => getenv('BIZCALL_ID'),
                    'mmdd' => date('md'),
                    'auth' => base64_encode(md5(sprintf('%s%s', getenv('BIZCALL_ID'), date('md'))))
                ]
            ]);

            return match (data_get($response, 'rt')) {
                0 => $response,
                default => throw new BadRequestHttpException(data_get($response, 'rs'))
            };
        } catch (BadRequestHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }
}
