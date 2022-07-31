<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class CalculateListMonth
 *
 * @property int $no
 * @property Carbon|null $dt_batch
 * @property Carbon $dt_calc_start
 * @property Carbon $dt_calc_end
 * @property int $no_shop
 * @property int|null $no_partner
 * @property string|null $cd_calc_period
 * @property int|null $nt_order_cnt
 * @property int|null $nt_product_cnt
 * @property float|null $at_price
 * @property float|null $at_price_pg
 * @property float|null $at_pg_commission
 * @property float|null $at_pg_vat
 * @property float|null $at_cpn_disct
 * @property float|null $at_cpn_disct_pg_commit
 * @property float|null $at_cpn_disct_pg_vat
 * @property float|null $at_cash_disct
 * @property float|null $at_cash_disct_pg
 * @property float|null $at_cash_disct_pg_vat
 * @property float|null $at_owin_commission
 * @property float|null $at_owin_vat
 * @property float|null $at_price_for_shop
 * @property float|null $at_price_next_for_calc
 *
 * @package App\Models
 */
class CalculateListMonth extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int',
        'no_shop' => 'int',
        'no_partner' => 'int',
        'nt_order_cnt' => 'int',
        'nt_product_cnt' => 'int',
        'at_price' => 'float',
        'at_price_pg' => 'float',
        'at_pg_commission' => 'float',
        'at_pg_vat' => 'float',
        'at_cpn_disct' => 'float',
        'at_cpn_disct_pg_commit' => 'float',
        'at_cpn_disct_pg_vat' => 'float',
        'at_cash_disct' => 'float',
        'at_cash_disct_pg' => 'float',
        'at_cash_disct_pg_vat' => 'float',
        'at_owin_commission' => 'float',
        'at_owin_vat' => 'float',
        'at_price_for_shop' => 'float',
        'at_price_next_for_calc' => 'float'
    ];

    protected $dates = [
        'dt_batch',
        'dt_calc_start',
        'dt_calc_end'
    ];

    protected $fillable = [
        'no',
        'dt_batch',
        'no_partner',
        'cd_calc_period',
        'nt_order_cnt',
        'nt_product_cnt',
        'at_price',
        'at_price_pg',
        'at_pg_commission',
        'at_pg_vat',
        'at_cpn_disct',
        'at_cpn_disct_pg_commit',
        'at_cpn_disct_pg_vat',
        'at_cash_disct',
        'at_cash_disct_pg',
        'at_cash_disct_pg_vat',
        'at_owin_commission',
        'at_owin_vat',
        'at_price_for_shop',
        'at_price_next_for_calc'
    ];
}
