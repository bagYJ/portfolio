<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class WashCouponEvent
 *
 * @property int $no
 * @property int $no_shop
 * @property float|null $at_disct_money
 * @property int|null $at_expire_day
 * @property float|null $at_create_min_price
 * @property float|null $at_create_max_price
 * @property string|null $yn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class WashCouponEvent extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_disct_money' => 'float',
        'at_expire_day' => 'int',
        'at_create_min_price' => 'float',
        'at_create_max_price' => 'float'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'at_disct_money',
        'at_expire_day',
        'at_create_min_price',
        'at_create_max_price',
        'yn_status',
        'id_upt',
        'dt_upt',
        'id_reg',
        'dt_reg'
    ];
}
