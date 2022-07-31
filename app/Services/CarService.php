<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CarList;
use App\Models\CarMaker;
use App\Models\MemberCarinfo;
use Illuminate\Support\Collection;

class CarService extends Service
{
    public function getMemberCarList($noUser)
    {
    }

    /**
     * 차량 조회
     * @param $parameter
     *
     * @return Collection
     */
    public static function getCars($parameter): Collection
    {
        return MemberCarinfo::where($parameter)->with(['carList','cards', 'member'])->get();
    }

    /**
     * 차량 제조사 조회
     *
     * @return Collection
     */
    public static function makerList(): Collection
    {
        return CarMaker::select('no_maker', 'nm_maker')->orderByRaw(
            "CASE WHEN `nm_maker` = '기타' THEN 2 ELSE 1 END, `nm_maker`"
        )->get();
    }

    /**
     * 차량 제조사 별 차종 조회
     *
     * @param int $noMaker
     * @return Collection
     */
    public static function kindByCarList(int $noMaker): Collection
    {
        return CarList::select('seq', 'ds_kind')->where('no_maker', $noMaker)->orderBy('ds_kind')->get();
    }
}
