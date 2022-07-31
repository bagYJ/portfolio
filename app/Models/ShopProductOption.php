<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopProductOption
 *
 * @property int $no
 * @property int|null $no_shop
 * @property int|null $no_group
 * @property int $no_option
 * @property string|null $nm_option
 * @property float|null $at_add_price
 * @property int|null $ct_order
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $cd_option_status
 *
 * @package App\Models
 */
class ShopProductOption extends Model
{
    protected $primaryKey = 'no_option';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no' => 'int',
        'no_shop' => 'int',
        'no_group' => 'int',
        'no_option' => 'int',
        'at_add_price' => 'float',
        'ct_order' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_shop',
        'no_group',
        'nm_option',
        'at_add_price',
        'ct_order',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'cd_option_status'
    ];
}
