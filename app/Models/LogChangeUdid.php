<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class LogChangeUdid
 *
 * @property int $no
 * @property int $no_user
 * @property string|null $ds_udid_before
 * @property string|null $ds_udid_after
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class LogChangeUdid extends Model
{
    protected $primaryKey = 'no';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = null;
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;


    protected $casts = [
        'no' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_upt'
    ];

    protected $fillable = [
        'no_user',
        'ds_udid_before',
        'ds_udid_after',
        'dt_upt'
    ];
}
