<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberLocationEnterLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int $no_user
 * @property int|null $no_shop
 * @property string|null $no_order
 * @property string|null $cd_pickup_status
 * @property float|null $shop_lat
 * @property float|null $shop_lng
 * @property float|null $user_lat
 * @property float|null $user_lng
 * @property string|null $yn_inside
 * @property string|null $ds_addr
 * @property string|null $ds_etc
 *
 * @package App\Models
 */
class MemberLocationEnterLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_shop' => 'int',
        'shop_lat' => 'float',
        'shop_lng' => 'float',
        'user_lat' => 'float',
        'user_lng' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'no_user',
        'no_shop',
        'no_order',
        'cd_pickup_status',
        'shop_lat',
        'shop_lng',
        'user_lat',
        'user_lng',
        'yn_inside',
        'ds_addr',
        'ds_etc'
    ];
}
