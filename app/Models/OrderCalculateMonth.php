<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class OrderCalculateMonth
 *
 * @property int $no
 * @property string $no_order
 * @property Carbon $dt_order
 * @property int $no_shop
 * @property string|null $cd_pg
 * @property float|null $at_pg_commission_rate
 * @property string|null $cd_commission_type
 * @property float|null $at_commission_amount
 * @property float|null $at_commission_rate
 * @property float|null $at_sales_commission_rate
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
 * @property float|null $at_pg_sales
 * @property float|null $at_pg_sales_vat
 * @property float|null $at_owin_commission
 * @property float|null $at_owin_commission_will
 * @property float|null $at_price_for_shop
 * @property float|null $at_price_for_shop_not_owin
 * @property float|null $at_price_next_for_calc
 * @property Carbon|null $dt_batch
 * @property Carbon|null $dt_send
 *
 * @package App\Models
 */
class OrderCalculateMonth extends Model
{
    protected $primaryKey = 'no_order';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int',
        'no_shop' => 'int',
        'at_pg_commission_rate' => 'float',
        'at_commission_amount' => 'float',
        'at_commission_rate' => 'float',
        'at_sales_commission_rate' => 'float',
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
        'at_pg_sales' => 'float',
        'at_pg_sales_vat' => 'float',
        'at_owin_commission' => 'float',
        'at_owin_commission_will' => 'float',
        'at_price_for_shop' => 'float',
        'at_price_for_shop_not_owin' => 'float',
        'at_price_next_for_calc' => 'float'
    ];

    protected $dates = [
        'dt_order',
        'dt_batch',
        'dt_send'
    ];

    protected $fillable = [
        'no',
        'dt_order',
        'no_shop',
        'cd_pg',
        'at_pg_commission_rate',
        'cd_commission_type',
        'at_commission_amount',
        'at_commission_rate',
        'at_sales_commission_rate',
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
        'at_pg_sales',
        'at_pg_sales_vat',
        'at_owin_commission',
        'at_owin_commission_will',
        'at_price_for_shop',
        'at_price_for_shop_not_owin',
        'at_price_next_for_calc',
        'dt_batch',
        'dt_send'
    ];
}
