<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Administrator;
use App\Models\PartnerManager;
use Illuminate\Support\Collection;

class AdminService extends Service
{
    public function adminList(): Collection
    {
        return Administrator::whereNotNull('ds_email')->get();
    }

    public static function getPartnerManager(array $parameter, ?array $whereNotNull = []): Collection
    {
        return PartnerManager::where($parameter)
            ->whereNotNull($whereNotNull)->get();
    }
}
