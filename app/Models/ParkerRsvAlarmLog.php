<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkerRsvAlarmLog
 *
 * @property int|null $no_rsv
 * @property Carbon|null $dt_reg
 * @property string|null $cd_rsv_alarm
 * @property int|null $no_user
 *
 * @package App\Models
 */
class ParkerRsvAlarmLog extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_rsv' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_rsv',
        'dt_reg',
        'cd_rsv_alarm',
        'no_user'
    ];
}
