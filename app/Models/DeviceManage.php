<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class DeviceManage
 *
 * @property int $no
 * @property int|null $no_device
 * @property int|null $no_order
 * @property string|null $ds_qrcode
 * @property string $ds_sn
 * @property string $ds_macaddr
 * @property string|null $cd_device
 * @property string $cd_shop
 * @property string|null $ds_firmware
 * @property string|null $ds_memo
 * @property string|null $cd_device_status
 * @property Carbon|null $dt_open
 * @property Carbon|null $dt_pairing
 * @property Carbon|null $dt_device_regist
 * @property string|null $yn_present
 * @property Carbon|null $dt_present
 * @property string|null $cd_device_opt
 * @property string|null $cd_distribute
 * @property string|null $cd_beacon_type
 *
 * @package App\Models
 */
class DeviceManage extends Model
{
    protected $primaryKey = 'ds_sn';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int',
        'no_device' => 'int',
        'no_order' => 'int'
    ];

    protected $dates = [
        'dt_open',
        'dt_pairing',
        'dt_device_regist',
        'dt_present'
    ];

    protected $fillable = [
        'no',
        'no_device',
        'no_order',
        'ds_qrcode',
        'ds_macaddr',
        'cd_device',
        'cd_shop',
        'ds_firmware',
        'ds_memo',
        'cd_device_status',
        'dt_open',
        'dt_pairing',
        'dt_device_regist',
        'yn_present',
        'dt_present',
        'cd_device_opt',
        'cd_distribute',
        'cd_beacon_type'
    ];
}
