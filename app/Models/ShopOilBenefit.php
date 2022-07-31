<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopOilBenefit
 *
 * @property int $no
 * @property int $no_partner
 * @property int $no_shop
 * @property string|null $yn_show_btn
 * @property Carbon|null $dt_reg
 * @property string|null $id_reg
 * @property Carbon|null $dt_upt
 * @property string|null $id_upt
 *
 * @package App\Models
 */
class ShopOilBenefit extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_partner' => 'int',
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_partner',
        'no_shop',
        'yn_show_btn',
        'dt_reg',
        'id_reg',
        'dt_upt',
        'id_upt'
    ];
}
