<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CodeService;
use App\Services\PartnerService;
use App\Utils\Common;
use Illuminate\Http\Request;

class Partner extends Controller
{
    public function gets(Request $request)
    {
        $request->validate([
            'cd_biz_kind_detail' => 'nullable|string',
        ]);

        $partners = PartnerService::gets();
        if (count($partners)) {
            foreach ($partners as $index => $partner) {
                $partners[$index]['ds_bi'] = Common::getImagePath($partner['ds_bi']);
                $partners[$index]['ds_pin'] = Common::getImagePath($partner['ds_pin']);
            }
        }

        return response()->json([
            'result' => true,
            'list_biz_kind_detail' => CodeService::getGroupCode('203')->filter(function ($query) {
                return $query->yn_status == 'Y' && $query->no_code != '203601';
            })->sortBy('no_code')->values(),
            'list_partner' => $partners
        ]);
    }
}
