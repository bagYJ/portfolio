<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class SaleOrderList
 *
 * @property int $no
 * @property string $no_order
 * @property int|null $no_payment_last
 * @property string $nm_order
 * @property int $no_user
 * @property int $no_partner
 * @property int $no_shop
 * @property string $cd_service
 * @property int|null $cd_service_pay
 * @property string $cd_payment
 * @property string|null $cd_payment_kind
 * @property string|null $cd_payment_method
 * @property string $cd_order_status
 * @property string $cd_pickup_status
 * @property string|null $cd_payment_status
 * @property Carbon|null $dt_order_status
 * @property Carbon|null $dt_pickup_status
 * @property Carbon|null $dt_payment_status
 * @property int $no_product
 * @property int|null $ct_inven
 * @property float|null $at_price_product
 * @property float $at_price
 * @property float|null $at_price_pg
 * @property int|null $no_card
 * @property string|null $cd_pg
 * @property string|null $ds_pg_id
 * @property string|null $ds_cpn_no
 * @property int|null $at_disct
 * @property int|null $at_cpn_disct
 * @property int|null $at_cash_disct
 * @property string|null $cd_calc_status
 * @property int|null $no_promotion_deal
 * @property string|null $no_order_coupon
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class SaleOrderList extends Model
{
    protected $primaryKey = 'no_order';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_payment_last' => 'int',
        'no_user' => 'int',
        'no_partner' => 'int',
        'no_shop' => 'int',
        'cd_service_pay' => 'int',
        'no_product' => 'int',
        'ct_inven' => 'int',
        'at_price_product' => 'float',
        'at_price' => 'float',
        'at_price_pg' => 'float',
        'no_card' => 'int',
        'at_disct' => 'int',
        'at_cpn_disct' => 'int',
        'at_cash_disct' => 'int',
        'no_promotion_deal' => 'int'
    ];

    protected $dates = [
        'dt_order_status',
        'dt_pickup_status',
        'dt_payment_status',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_payment_last',
        'nm_order',
        'no_user',
        'no_partner',
        'no_shop',
        'cd_service',
        'cd_service_pay',
        'cd_payment',
        'cd_payment_kind',
        'cd_payment_method',
        'cd_order_status',
        'cd_pickup_status',
        'cd_payment_status',
        'dt_order_status',
        'dt_pickup_status',
        'dt_payment_status',
        'no_product',
        'ct_inven',
        'at_price_product',
        'at_price',
        'at_price_pg',
        'no_card',
        'cd_pg',
        'ds_pg_id',
        'ds_cpn_no',
        'at_disct',
        'at_cpn_disct',
        'at_cash_disct',
        'cd_calc_status',
        'no_promotion_deal',
        'no_order_coupon',
        'dt_reg'
    ];
}
