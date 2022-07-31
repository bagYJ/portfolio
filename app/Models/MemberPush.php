<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberPush
 *
 * @property int $no_user
 * @property string $cd_service
 * @property string|null $cd_phone_os
 * @property string $yn_real
 * @property string|null $ds_phone_token
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class MemberPush extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = null;
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;


    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_upt'
    ];

    protected $hidden = [
        'ds_phone_token'
    ];

    protected $fillable = [
        'cd_phone_os',
        'ds_phone_token',
        'dt_upt'
    ];
}
