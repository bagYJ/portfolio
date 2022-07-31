<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CodeManage;
use Illuminate\Support\Collection;

class CodeService extends Service
{
    public static function getCode(?string $code): ?CodeManage
    {
        return CodeManage::where('no_code', $code)->first();
    }

    public static function getGroupCode(string $group): Collection
    {
        return CodeManage::where('no_group', $group)->get();
    }
}
