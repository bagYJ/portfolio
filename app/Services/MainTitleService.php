<?php

namespace App\Services;

use App\Enums\SearchBizKind;
use App\Models\MainTitle;

class MainTitleService
{

    public static function getRandomTitle()
    {
        return MainTitle::select([
            'cd_biz_kind',
            'text',
            'app_route',
        ])->where('yn_use', 'Y')->inRandomOrder()->get()->map(function ($collect) {
            $collect->cd_biz_kind = SearchBizKind::getBizKind($collect->cd_biz_kind)->name;
            return $collect;
        })->first();
    }

}
