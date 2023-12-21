<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Utils\Code;
use App\Utils\Common;
use Exception;
use Illuminate\Support\Facades\Log;

abstract class GasAbstract
{
    /**
     * @param string $header
     * @param string $body
     * @return string
     * @throws Exception
     */
    public static function socket(string $header, string $body): string
    {
        $response = Common::client(Method::POST, sprintf('%s%s', getenv('GS_PROXY_IP'), getenv('GS_PROXY_PATH_COUPON')), [
            'form_params' => [
                'ip' => getenv('GAS_IP'),
                'port' => getenv('GAS_PORT'),
                'parameter' => base64_encode($body),
            ]
        ]);

        $result = json_decode($response->getBody()->getContents());
        Log::channel('client')->info(sprintf('%s %s RESPONSE ', getenv('GS_PROXY_IP'), Method::POST->name), [$result]);

        return match ($result->code) {
            '0000' => base64_decode($result->message),
            default => throw new Exception(sprintf(Code::message($result->code), $result->message))
        };
    }

    private static function makeSocketData(string $header, string $body): string
    {
        return sprintf('%s%s%s%s%s', chr(2), self::makeData($header, $body), $header, $body, chr(3));
    }

    private static function makeData(string $header, string $body): string
    {
        return sprintf('%04d', strlen(sprintf('%s%s%s', $header, $body, chr(3))));
    }
}
