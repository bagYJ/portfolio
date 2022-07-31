<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PromotionContractOilLog
 *
 * @property int $no
 * @property Carbon $dt_reg
 * @property int $no_user
 * @property string|null $no_order
 * @property int|null $no_deal
 * @property float|null $at_liter_real
 * @property float|null $at_sum_liter
 * @property string $yn_success
 * @property string|null $ds_etc
 *
 * @package App\Models
 */
class PromotionContractOilLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $casts = [
        'no_user' => 'int',
        'no_deal' => 'int',
        'at_liter_real' => 'float',
        'at_sum_liter' => 'float'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'dt_reg',
        'no_user',
        'no_order',
        'no_deal',
        'at_liter_real',
        'at_sum_liter',
        'yn_success',
        'ds_etc'
    ];
}
