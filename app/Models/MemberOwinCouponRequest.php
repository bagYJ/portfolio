<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberOwinCouponRequest
 *
 * @property int $no
 * @property string|null $no_pin
 * @property int|null $no_deal
 * @property int $no_user
 * @property string $yn_success
 * @property string|null $reg_code
 * @property string|null $reg_msg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberOwinCouponRequest extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_deal' => 'int',
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_pin',
        'no_deal',
        'no_user',
        'yn_success',
        'reg_code',
        'reg_msg',
        'dt_reg'
    ];
}
