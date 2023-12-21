<?php
declare(strict_types=1);

namespace App\Services\Dev;

use App\Abstracts\BizcallAbstract;
use App\Requests\Bizcall\AutoMapping;
use Exception;

class Bizcall extends BizcallAbstract
{
    public static function autoMapping(AutoMapping $mapping): array
    {
        return ['vn' => getenv('BIZCALL_DEFAULT_NUMBER'), 'rt' => 0, 'rs' => ''];
    }

    /**
     * @param string $virtualNumber
     * @return array
     * @throws Exception
     */
    public static function closeMapping(string $virtualNumber): array
    {
        return ['rt' => 0, 'rs' => ''];
    }
}
