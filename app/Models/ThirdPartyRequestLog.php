<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ThirdPartyRequestLog
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $cd_third_party
 * @property string|null $ds_access_token
 * @property string|null $uid
 * @property string|null $nm_third_party
 * @property string|null $return_code
 * @property string|null $errid
 * @property string|null $errcode
 * @property string|null $errmsg
 * @property string|null $user_return_code
 * @property string|null $user_errid
 * @property string|null $user_errcode
 * @property string|null $user_errmsg
 * @property string|null $agree_return_status
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ThirdPartyRequestLog extends Model
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
        'dt_reg'
    ];

    protected $hidden = [
        'ds_access_token'
    ];

    protected $fillable = [
        'no_user',
        'cd_third_party',
        'ds_access_token',
        'uid',
        'nm_third_party',
        'return_code',
        'errid',
        'errcode',
        'errmsg',
        'user_return_code',
        'user_errid',
        'user_errcode',
        'user_errmsg',
        'agree_return_status',
        'dt_reg'
    ];
}
