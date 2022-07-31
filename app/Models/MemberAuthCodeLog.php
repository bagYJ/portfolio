<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberAuthCodeLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int $no_user
 * @property string|null $oauth_code
 * @property Carbon|null $expir_time
 * @property string|null $yn_auth
 *
 * @package App\Models
 */
class MemberAuthCodeLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'expir_time'
    ];

    protected $fillable = [
        'dt_reg',
        'no_user',
        'oauth_code',
        'expir_time',
        'yn_auth'
    ];
}
