<?php
declare(strict_types=1);

namespace App\Traits;

use App\Utils\Code;

trait Enum
{
    /**
     * @param string|null $code
     * @return Enum|null
     */
    public static function case(?string $code): ?static
    {
        $keys = static::keys();
        $key = array_search($code, $keys);

        return is_numeric($key) ? self::cases()[$key] : null;
    }

    public static function keys(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function text(string|int $value): string|int|null
    {
        return Code::code($value);
    }
}