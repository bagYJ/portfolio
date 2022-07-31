<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class CouponAutoPublish
 *
 * @property int $no
 * @property string $cd_third_party
 * @property string $nm_user
 * @property string $ds_phone
 * @property string $ds_access_vin_rsm
 * @property int $no_user
 * @property int $no_event
 * @property string $coupon_type
 * @property int $coupon_key
 * @property Carbon|null $dt_use_start
 * @property Carbon|null $dt_use_end
 * @property string|null $coupon_issue
 * @property string|null $no_coupon
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 *
 * @package App\Models
 */
class CouponAutoPublish extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_event' => 'int',
        'coupon_key' => 'int'
    ];

    protected $dates = [
        'dt_use_start',
        'dt_use_end',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'cd_third_party',
        'nm_user',
        'ds_phone',
        'ds_access_vin_rsm',
        'no_user',
        'no_event',
        'coupon_type',
        'coupon_key',
        'dt_use_start',
        'dt_use_end',
        'coupon_issue',
        'no_coupon',
        'dt_reg',
        'dt_upt'
    ];
}
