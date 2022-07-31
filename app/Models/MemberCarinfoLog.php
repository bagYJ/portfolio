<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCarinfoLog
 *
 * @property int $no
 * @property int $no_user
 * @property int|null $seq
 * @property string|null $ds_etc_kind
 * @property string $ds_car_number
 * @property string|null $ds_car_search
 * @property string|null $cd_gas_kind
 * @property string|null $ds_chk_rssi_where
 * @property int $no_device
 * @property string|null $ds_adver
 * @property string|null $yn_main_car
 * @property string|null $yn_delete
 * @property string|null $ds_sn
 * @property Carbon|null $dt_device_update
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberCarinfoLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'seq' => 'int',
        'no_device' => 'int',
        'ds_adver' => 'string'
    ];

    protected $dates = [
        'dt_device_update',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'seq',
        'ds_etc_kind',
        'ds_car_number',
        'ds_car_search',
        'cd_gas_kind',
        'ds_chk_rssi_where',
        'no_device',
        'ds_adver',
        'yn_main_car',
        'yn_delete',
        'ds_sn',
        'dt_device_update',
        'dt_reg'
    ];
}
