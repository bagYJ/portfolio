<?php
declare(strict_types=1);

namespace App\Utils;

use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

/**
 * @method static code(?string $value)
 * @method static message(?string $value)
 * @method static gasType(?string $value)
 * @method static pgResponse(?string $value)
 */
class Code
{
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        return Arr::get(self::yml($name), $arguments[0] ?? null);
    }

    public static function yml(string $file): array
    {
        return Yaml::parseFile(storage_path(sprintf('/yml/%s.yml', Common::camelToSnake($file))));
    }
}
