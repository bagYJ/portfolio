<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\RetailAdminChkLog;

class RetailAdminCheckLogService extends Service
{
    public static function insertRetailAdminChkLog($data)
    {
        RetailAdminChkLog::create($data);
    }
}
