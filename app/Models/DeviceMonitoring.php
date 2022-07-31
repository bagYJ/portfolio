<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class DeviceMonitoring
 *
 * @property int $no
 * @property int $no_shop
 * @property string|null $cd_device
 * @property string|null $yn_device_status
 * @property string|null $no_dp
 * @property string|null $ds_firmware
 * @property string|null $ds_etc
 * @property string|null $ds_mail_addr
 * @property string|null $ds_mail_result
 * @property string|null $yn_test
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_send
 * @property Carbon|null $dt_send2
 *
 * @package App\Models
 */
class DeviceMonitoring extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_send',
        'dt_send2'
    ];

    protected $fillable = [
        'no_shop',
        'cd_device',
        'yn_device_status',
        'no_dp',
        'ds_firmware',
        'ds_etc',
        'ds_mail_addr',
        'ds_mail_result',
        'yn_test',
        'dt_reg',
        'dt_send',
        'dt_send2'
    ];
}
