<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Partner;

class PartnerService extends Service
{

    public static function gets($bizKindDetail = null)
    {
        $where = [
            'yn_status' => 'Y'
        ];
        if ($bizKindDetail) {
            $where['cd_biz_kind_detail'] = $bizKindDetail;
        }

        return Partner::where($where)
            ->whereNotIn('cd_biz_kind', ['201999', '201600'])
            ->orderBy('no', 'DESC')
            ->get();
    }

    public static function get($noPartner)
    {
        return Partner::where([
            'no_partner' => $noPartner
        ])->first();
    }
}
