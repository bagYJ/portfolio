<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;


/**
 * Class ShopInshop
 *
 * @property int $no_shop
 * @property int $no_shop_in
 *
 * @package App\Models
 */
class ShopInshop extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_shop' => 'int',
        'no_shop_in' => 'int'
    ];
}
