<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberShopEnterLogWash
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int $no_user
 * @property int $no_shop
 * @property string|null $ds_adver
 * @property string|null $ds_car_number
 * @property string|null $cd_alarm_event_type
 * @property string|null $no_order
 * @property float|null $at_price
 * @property string|null $cd_pg_bill_result
 * @property int|null $no_payment
 *
 * @package App\Models
 */
class MemberShopEnterLogWash extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int',
        'at_price' => 'float',
        'no_payment' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'no_user',
        'no_shop',
        'ds_adver',
        'ds_car_number',
        'cd_alarm_event_type',
        'no_order',
        'at_price',
        'cd_pg_bill_result',
        'no_payment'
    ];
}
