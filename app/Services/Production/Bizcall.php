<?php
declare(strict_types=1);

namespace App\Services\Production;

use App\Abstracts\BizcallAbstract;
use App\Enums\Method;
use App\Requests\Bizcall\AutoMapping;
use Exception;
use Throwable;

class Bizcall extends BizcallAbstract
{
    /**
     * @param AutoMapping $mapping
     * @return array
     */
    public static function autoMapping(AutoMapping $mapping): array
    {
        try {
            $response = self::client(Method::POST, getenv('BIZCALL_PATH_LINK'), [
                'form_params' => [
                    'iid' => getenv('BIZCALL_ID'),
                    'rn' => $mapping->realNumber,
                    'auth' => base64_encode(md5(sprintf('%s%s', getenv('BIZCALL_ID'), $mapping->realNumber))),
                    'memo' => $mapping->cdBizKind,
                    'memo2' => $mapping->noOrder
                ]
            ]);

            return match (data_get($response, 'rt')) {
                0 => $response,
                default => ['vn' => getenv('BIZCALL_SERVICE_CENTER_NUMBER'), 'rt' => 0, 'rs' => '']
            };
        } catch (Throwable) {
            return ['vn' => getenv('BIZCALL_SERVICE_CENTER_NUMBER'), 'rt' => 0, 'rs' => ''];
        }
    }

    /**
     * @param string $virtualNumber
     * @return array
     * @throws Exception
     */
    public static function closeMapping(string $virtualNumber): array
    {
        try {
            if (in_array($virtualNumber, [getenv('BIZCALL_DEFAULT_NUMBER'), getenv('BIZCALL_SERVICE_CENTER_NUMBER')])) {
                return ['rt' => 0, 'rs' => ''];
            }
            $response = self::client(Method::POST, getenv('BIZCALL_PATH_UNLINK'), [
                'form_params' => [
                    'iid' => getenv('BIZCALL_ID'),
                    'vn' => $virtualNumber,
                    'rn' => ' ',
                    'auth' => base64_encode(md5(sprintf('%s%s', getenv('BIZCALL_ID'), $virtualNumber)))
                ]
            ]);

            return match (data_get($response, 'rt')) {
                0 => $response,
                default => throw new Exception(data_get($response, 'rs'))
            };
        } catch (Throwable $t) {
            throw new Exception($t->getMessage());
        }
    }
}
