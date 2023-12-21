<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCoupon
 *
 * @property int $no_user
 * @property int $no_event
 * @property string|null $nm_event
 * @property string $cd_mcp_status
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $no_order
 * @property string|null $ds_etc
 * @property string|null $id_admin
 * @property int|string|null $at_discount
 * @property string|null $cd_disc_type
 * @property string $coupon_type
 * @property string $discount_type
 * @property string|null $available_partner
 * @property string|null $available_shop
 * @property string|null $available_card
 * @property string|null $available_weekday
 * @property string|null $available_category
 * @property string|null $available_product
 * @property string|null $at_price_limit
 * @property string $yn_condi_status_partner
 * @property string $yn_condi_status_shop
 * @property string $yn_condi_status_weekday
 * @property string $yn_condi_status_category
 * @property string $yn_condi_status_menu
 * @property string $yn_condi_status_money
 * @property float|null $at_max_disc
 * @property Carbon|null $dt_use_end
 * @property int $no
 * @property int $no_coupon
 * @property OrderList $orderList
 *
 * @package App\Models
 */
class MemberCoupon extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_event' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt',
        'dt_use_end',
    ];

    protected $fillable = [
        'no_user',
        'no_coupon',
        'no_event',
        'cd_mcp_status',
        'dt_reg',
        'dt_upt',
        'no_order',
        'ds_etc',
        'id_admin',
        'dt_use_start',
        'dt_use_end',
    ];


}
