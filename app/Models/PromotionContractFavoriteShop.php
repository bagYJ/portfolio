<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PromotionContractFavoriteShop
 *
 * @property int $no
 * @property int $no_user
 * @property int $no_contract
 * @property int|null $no_shop_1
 * @property float|null $at_sum_liter_1
 * @property int|null $no_shop_2
 * @property float|null $at_sum_liter_2
 * @property int|null $no_shop_3
 * @property float|null $at_sum_liter_3
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class PromotionContractFavoriteShop extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_contract' => 'int',
        'no_shop_1' => 'int',
        'at_sum_liter_1' => 'float',
        'no_shop_2' => 'int',
        'at_sum_liter_2' => 'float',
        'no_shop_3' => 'int',
        'at_sum_liter_3' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_contract',
        'no_shop_1',
        'at_sum_liter_1',
        'no_shop_2',
        'at_sum_liter_2',
        'no_shop_3',
        'at_sum_liter_3',
        'dt_reg'
    ];
}
