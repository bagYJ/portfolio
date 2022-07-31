<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class RetailShopProductStock
 *
 * @property int $no
 * @property int $no_partner
 * @property int $no_shop
 * @property int $no_product
 * @property int|null $cnt_product
 * @property string|null $yn_soldout
 * @property Carbon|null $dt_soldout
 * @property Carbon|null $dt_upt
 * @property Carbon $dt_reg
 *
 * @package App\Models
 */
class RetailShopProductStock extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_partner' => 'int',
        'no_shop' => 'int',
        'no_product' => 'int',
        'cnt_product' => 'int'
    ];

    protected $dates = [
        'dt_soldout',
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_partner',
        'no_shop',
        'no_product',
        'cnt_product',
        'yn_soldout',
        'dt_soldout',
        'dt_upt',
        'dt_reg'
    ];
}
