<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BbsNotice;
use Illuminate\Support\Collection;

class EventService extends Service
{
    public static function getBanner(): Collection
    {
        return BbsNotice::where([
            'yn_show' => 'Y',
            'cd_service' => '900100',
            'yn_popup' => 'Y'
        ])->whereNotNull('ds_popup_thumb')->orderByDesc('yn_prior_popup')->orderByDesc('no')->get();
    }
}
