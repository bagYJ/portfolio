<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EvChargerParkingFreeYN;
use App\Models\EvCharger;
use App\Utils\Code;
use Illuminate\Support\Collection;

class EvChargerService extends Service
{
    /**
     * @return array
     */
    public static function getFilter(): array
    {
        return [
            'type' => Code::evcharger('type'),
            'company' => Code::evcharger('busi_id'),
            'parking_free_yn' => EvChargerParkingFreeYN::options()
        ];
    }

    /**
     * @param string $idStat
     * @return Collection
     */
    public static function getInfo(string $idStat)
    {
        return EvCharger::with(
            'evChargerMachine'
        )->where(
            'id_stat',
            $idStat
        )->whereNotNull('at_ev_price')->get()->map(function ($list) {
            return [
                'id_stat' => $list->id_stat,
                'nm_stat' => $list->nm_stat,
                'yn_limit' => $list->yn_limit,
                'ds_busi_tel' => $list->ds_busi_tel,
                'ds_addr' => $list->ds_addr,
                'ds_use_time' => $list->ds_use_time,
                'ds_lat' => $list->ds_lat,
                'ds_lng' => $list->ds_lng,
                'at_ev_price' => $list->at_ev_price,
                'ds_note' => $list->ds_note,
                'ds_limit' => $list->ds_limit,
                'items' => $list->evChargerMachine->map(function ($item) {
                    return [
                        'id_chger' => $item->id_chger,
                        'cd_chger_stat' => $item->cd_chger_stat,
                        'cd_chger_type' => $item->cd_chger_type,
                        'ds_output' => $item->ds_output,
                        'ds_method' => $item->ds_method,
                    ];
                })
            ];
        });
    }
}
