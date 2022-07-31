<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class CouponEventAuto
 *
 * @property int $no_auto_cpn_event
 * @property int $no_shop
 * @property float $at_price
 * @property float $at_cpn_price
 * @property int $nt_expire_day
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * @property string $yn_status
 * @property string|null $ds_title
 *
 * @package App\Models
 */
class CouponEventAuto extends Model
{
    protected $primaryKey = 'no_auto_cpn_event';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_price' => 'float',
        'at_cpn_price' => 'float',
        'nt_expire_day' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_shop',
        'at_price',
        'at_cpn_price',
        'nt_expire_day',
        'dt_reg',
        'dt_upt',
        'yn_status',
        'ds_title'
    ];
}
