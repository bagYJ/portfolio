<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class AdminSecurityLog
 *
 * @property Carbon $dt_reg
 * @property string $id_admin
 * @property string $ds_log_type
 * @property string $ds_log
 *
 * @package App\Models
 */
class AdminSecurityLog extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'id_admin',
        'ds_log_type',
        'ds_log'
    ];
}
