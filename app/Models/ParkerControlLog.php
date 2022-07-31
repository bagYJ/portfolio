<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkerControlLog
 *
 * @property int $no
 * @property int|null $no_device
 * @property string|null $cd_parker_status
 * @property string|null $yn_control
 * @property string|null $ds_server_ip
 * @property int|null $at_parker_status
 * @property string|null $ds_remain_volt
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ParkerControlLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_device' => 'int',
        'at_parker_status' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_device',
        'cd_parker_status',
        'yn_control',
        'ds_server_ip',
        'at_parker_status',
        'ds_remain_volt',
        'dt_reg'
    ];
}
