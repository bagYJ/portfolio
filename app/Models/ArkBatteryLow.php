<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ArkBatteryLow
 *
 * @property int $no_shop
 * @property string $ds_display_ark_id
 * @property string $cd_ark_status
 * @property Carbon $dt_reg
 *
 * @package App\Models
 */
class ArkBatteryLow extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg'
    ];
}
