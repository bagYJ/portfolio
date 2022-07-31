<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class PromotionContractOil
 *
 * @property int $no
 * @property int $no_user
 * @property float $at_contract_liter
 * @property float|null $at_sum_liter
 * @property string $yn_success
 * @property int|null $no_deal
 * @property string|null $dt_year
 * @property string|null $dt_month
 * @property Carbon|null $dt_start_reg
 * @property Carbon|null $dt_end_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class PromotionContractOil extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'at_contract_liter' => 'float',
        'at_sum_liter' => 'float',
        'no_deal' => 'int'
    ];

    protected $dates = [
        'dt_start_reg',
        'dt_end_reg',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'at_contract_liter',
        'at_sum_liter',
        'yn_success',
        'no_deal',
        'dt_year',
        'dt_month',
        'dt_start_reg',
        'dt_end_reg',
        'dt_reg'
    ];
}
