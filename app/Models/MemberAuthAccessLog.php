<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberAuthAccessLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int|null $no_user
 * @property string|null $cd_third_party
 * @property string|null $cd_access_status
 * @property string|null $oauth_code
 * @property Carbon|null $expir_time
 * @property string|null $yn_auth
 * @property string|null $ds_access_token
 * @property string|null $ds_access_vin
 * @property string|null $yn_account_status
 *
 * @package App\Models
 */
class MemberAuthAccessLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'expir_time'
    ];

    protected $hidden = [
        'ds_access_token'
    ];

    protected $fillable = [
        'dt_reg',
        'no_user',
        'cd_third_party',
        'cd_access_status',
        'oauth_code',
        'expir_time',
        'yn_auth',
        'ds_access_token',
        'ds_access_vin',
        'yn_account_status'
    ];
}
