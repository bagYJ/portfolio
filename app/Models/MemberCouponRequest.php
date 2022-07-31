<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCouponRequest
 *
 * @property int $no
 * @property int $no_user
 * @property int $no_partner
 * @property Carbon $dt_reg
 * @property string $ds_cpn_no
 * @property string $yn_success
 * @property string|null $reg_msg
 *
 * @package App\Models
 */
class MemberCouponRequest extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_partner' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_partner',
        'dt_reg',
        'ds_cpn_no',
        'yn_success',
        'reg_msg'
    ];
}
