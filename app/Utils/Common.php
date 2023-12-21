<?php
declare(strict_types=1);

namespace App\Utils;



use App\Enums\AppType;
use App\Enums\Method;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Owin\OwinCommonUtil\Enums\ServiceCodeEnum;
use Psr\Http\Message\ResponseInterface;

class Common
{
    private static array $addOptions = [
        'timeout' => 10,
        'http_errors' => false
    ];

    /**
     * @param string $name
     * @return mixed
     */
    public static function getService(string $name): mixed
    {
        return new (sprintf('\\App\\Services\\%s\\%s', ucwords(getenv('APP_ENV')), $name));
    }

    /**
     * @param Method $method
     * @param string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public static function client(Method $method, string $uri, array $options): ResponseInterface
    {
        Log::channel('client')->info(sprintf('%s %s REQUEST ', $uri, $method->name), $options);
        return (new Client())->request($method->name, $uri, $options + self::$addOptions);
    }

    public static function getHash(string $data, ?string $algo = 'sha256'): string
    {
        return hash($algo, $data);
    }

    public static function makeCuRequest(): Request
    {
        $opts = ['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: Application/json',
            'timeout' => getenv('CLIENT_TIMEOUT')
        ]];
        $context = stream_context_create($opts);
        $post = file_get_contents('php://input', true, $context);

        return (new Request())->merge(json_decode(base64_decode($post), true));
    }

    /**
     * @param string|int $text
     * @param string $iv
     * @param string $key
     * @param string|null $algo
     * @return string|int
     */
    public static function encrypt(string|int $text, string $iv, string $key, ?string $algo = 'AES-256-CBC'): string|int
    {
        return base64_encode(openssl_encrypt($text, $algo, $key, OPENSSL_RAW_DATA, $iv));
    }

    /**
     * @param string $text
     * @param string $iv
     * @param string $key
     * @param string|null $algo
     * @return string|int
     */
    public static function decrypt(string $text, string $iv, string $key, ?string $algo = 'AES-256-CBC'): string|int
    {
        return openssl_decrypt(base64_decode($text), $algo, $key, OPENSSL_RAW_DATA, $iv);
    }

    public static function camelToSnake(string $value): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }

    /**
     * ServiceCodeEnum 과 AppType 매칭
     * @param ServiceCodeEnum $serviceCodeEnum
     * @return AppType
     */
    public static function getAppTypeFromServiceCodeEnum(ServiceCodeEnum $serviceCodeEnum): AppType {
        return match ($serviceCodeEnum) {
            ServiceCodeEnum::GTCS => AppType::GTCS,
            ServiceCodeEnum::RENAULT => AppType::AVN,
            ServiceCodeEnum::TMAP => AppType::TMAP_AUTO,
            default => AppType::OWIN
        };
    }
}
