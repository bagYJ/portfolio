<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Postback
 *
 * @property int $no
 * @property string|null $batch_key
 * @property string|null $batch_kind
 * @property string|null $ds_result
 * @property string|null $ds_result_msg
 * @property Carbon|null $dt_reg
 * @property float|null $at_process
 *
 * @package App\Models
 */
class Postback extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $casts = [
        'at_process' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'batch_key',
        'batch_kind',
        'ds_result',
        'ds_result_msg',
        'dt_reg',
        'at_process'
    ];
}
