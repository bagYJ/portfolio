<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopOilPriceHistory
 *
 * @property int $no
 * @property int $no_shop
 * @property string $cd_gas_kind
 * @property string $ds_uni
 * @property string $ds_prod
 * @property float|null $at_price
 * @property Carbon|null $dt_trade
 * @property Carbon|null $tm_trade
 * @property Carbon|null $dt_update
 * @property float|null $at_discnt_liter
 * @property Carbon|null $dt_reg
 * @property string|null $ds_op_type
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class ShopOilPriceHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
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
        'dt_update',
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'cd_gas_kind',
        'ds_uni',
        'ds_prod',
        'at_price',
        'dt_trade',
        'tm_trade',
        'dt_update',
        'at_discnt_liter',
        'dt_reg',
        'ds_op_type',
        'id_admin'
    ];
}
