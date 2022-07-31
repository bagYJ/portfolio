<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ArkBatteryLowLog
 *
 * @property int $no
 * @property int|null $no_shop
 * @property string|null $ds_display_ark_id
 * @property string|null $cd_ark_status
 * @property Carbon|null $dt_battery_low
 * @property Carbon|null $dt_battery_ok
 *
 * @package App\Models
 */
class ArkBatteryLowLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_battery_low',
        'dt_battery_ok'
    ];

    protected $fillable = [
        'no_shop',
        'ds_display_ark_id',
        'cd_ark_status',
        'dt_battery_low',
        'dt_battery_ok'
    ];
}
