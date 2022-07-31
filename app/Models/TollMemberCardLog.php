<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class TollMemberCardLog
 *
 * @property string $ds_trn_key
 * @property int $no_user
 * @property string $ds_phone
 * @property string|null $yn_delete
 * @property Carbon|null $dt_reg
 * @property string|null $ds_trn_date
 * @property string|null $ds_trn_time
 * @property string|null $ds_car_number
 * @property string|null $ds_car_type
 * @property string|null $ds_res_code
 * @property string|null $ds_res_msg
 * @property string|null $ds_etc
 *
 * @package App\Models
 */
class TollMemberCardLog extends Model
{
    protected $primaryKey = 'ds_trn_key';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'ds_phone',
        'yn_delete',
        'dt_reg',
        'ds_trn_date',
        'ds_trn_time',
        'ds_car_number',
        'ds_car_type',
        'ds_res_code',
        'ds_res_msg',
        'ds_etc'
    ];
}
