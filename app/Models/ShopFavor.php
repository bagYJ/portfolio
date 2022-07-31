<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopFavor
 *
 * @property int $no
 * @property int $no_user
 * @property int $no_shop
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ShopFavor extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_user' => 'int',
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'dt_reg'
    ];
}
