<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopCalc
 *
 * @property int $no_shop
 * @property string|null $cd_bank
 * @property string|null $ds_bank_acct
 * @property string|null $nm_acct_name
 * @property string|null $cd_calc_period
 * @property int|null $at_calc_day
 * @property string|null $ds_tax_email
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt_email
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class ShopCalc extends Model
{
    protected $primaryKey = 'no_shop';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_calc_day' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt_email'
    ];

    protected $fillable = [
        'cd_bank',
        'ds_bank_acct',
        'nm_acct_name',
        'cd_calc_period',
        'at_calc_day',
        'ds_tax_email',
        'dt_reg',
        'dt_upt_email',
        'id_admin'
    ];
}
