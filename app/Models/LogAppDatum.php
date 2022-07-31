<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class LogAppDatum
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $ds_flag
 * @property string|null $ds_param1
 * @property string|null $ds_param2
 * @property string|null $ds_param3
 * @property string|null $ds_param4
 * @property string|null $ds_param5
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class LogAppDatum extends Model
{
    protected $primaryKey = 'no';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'ds_flag',
        'ds_param1',
        'ds_param2',
        'ds_param3',
        'ds_param4',
        'ds_param5',
        'dt_reg'
    ];
}
