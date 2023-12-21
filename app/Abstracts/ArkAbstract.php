<?php
declare(strict_types=1);

namespace App\Abstracts;

use App\Enums\Method;
use App\Utils\Code;
use App\Utils\Common;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

abstract class ArkAbstract
{
    /**
     * @param string $header
     * @param string $body
     * @return string
     * @throws GuzzleException
     */
    public static function socket(string $header, string $body): string
    {
        $response = Common::client(Method::POST, sprintf('%s%s', getenv('GS_PROXY_IP'), getenv('GS_PROXY_PATH_SOCKET')), [
            'form_params' => [
                'ip' => getenv('ARK_IP'),
                'port' => getenv('ARK_PORT'),
                'parameter' => base64_encode(self::makeSocketData($header, $body)),
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
        return sprintf('%s%s%s%s%s', chr(2), self::makeData($body), pack('C', $header), $body, chr(3));
    }

    private static function makeData(string $body): string
    {
        $data = sprintf('%08d', strlen($body) + 2);
        return pack('CC', substr($data, 0, 4), substr($data, 4, 4));
    }
}
