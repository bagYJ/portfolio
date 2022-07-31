<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkerServerCommLog
 *
 * @property int $no
 * @property string $ds_sn
 * @property string|null $ds_remain_volt
 * @property int|null $at_parker_status
 * @property int|null $at_ir_sensor
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ParkerServerCommLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'at_parker_status' => 'int',
        'at_ir_sensor' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'ds_sn',
        'ds_remain_volt',
        'at_parker_status',
        'at_ir_sensor',
        'dt_reg'
    ];
}
