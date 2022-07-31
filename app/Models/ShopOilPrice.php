<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopOilPrice
 *
 * @property int $no_shop
 * @property string $cd_gas_kind
 * @property string $ds_uni
 * @property string $ds_prod
 * @property float|null $at_price
 * @property Carbon|null $dt_trade
 * @property Carbon|null $tm_trade
 * @property Carbon|null $dt_update
 * @property float|null $at_discnt_liter
 *
 * @package App\Models
 */
class ShopOilPrice extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = null;
    const UPDATED_AT = 'dt_update';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_price' => 'float',
        'at_discnt_liter' => 'float'
    ];

    protected $dates = [
        'dt_trade',
        'tm_trade',
        'dt_update'
    ];

    protected $fillable = [
        'ds_uni',
        'ds_prod',
        'at_price',
        'dt_trade',
        'tm_trade',
        'dt_update',
        'at_discnt_liter'
    ];
}
