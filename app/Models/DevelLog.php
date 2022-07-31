<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class DevelLog
 *
 * @property int $no
 * @property int|null $no_seq
 * @property int|null $no_user
 * @property string|null $ds_ctrl
 * @property string|null $ds_query
 * @property string|null $ds_server
 * @property string|null $ds_req
 * @property string|null $ds_res
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class DevelLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_seq' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_seq',
        'no_user',
        'ds_ctrl',
        'ds_query',
        'ds_server',
        'ds_req',
        'ds_res',
        'dt_reg'
    ];
}
