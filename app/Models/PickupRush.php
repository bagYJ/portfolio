<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PickupRush
 *
 * @property int $no
 * @property int|null $no_user
 * @property int $no_rush
 * @property int|null $no_partner
 * @property int|null $no_shop
 * @property float|null $at_price
 * @property string|null $cd_rush_type
 * @property string|null $ds_list_product
 * @property int|null $ct_click
 * @property int|null $ct_order
 * @property string|null $yn_main_rush
 * @property int|null $no_order_num
 * @property Carbon|null $dt_upt
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class PickupRush extends Model
{
    protected $primaryKey = 'no_rush';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;


    protected $casts = [
        'no' => 'int',
        'no_user' => 'int',
        'no_rush' => 'int',
        'no_partner' => 'int',
        'no_shop' => 'int',
        'at_price' => 'float',
        'ct_click' => 'int',
        'ct_order' => 'int',
        'no_order_num' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_user',
        'no_partner',
        'no_shop',
        'at_price',
        'cd_rush_type',
        'ds_list_product',
        'ct_click',
        'ct_order',
        'yn_main_rush',
        'no_order_num',
        'dt_upt',
        'dt_reg'
    ];
}
