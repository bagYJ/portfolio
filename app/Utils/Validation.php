<?php

declare(strict_types=1);

namespace App\Utils;

class Validation
{

    /**
     * code 값이 올바른 값이 아니면 TRUE
     * @param string $code
     * @param string $codeGroup
     * @return bool
     */
    public static function code(string $code, string $codeGroup): bool
    {
        return !!(substr($code, 0, 3) === $codeGroup && strlen($code) == 6);
    }


}
