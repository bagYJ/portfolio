<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberBenefitCoupon
 *
 * @property int $no
 * @property int $no_benefit
 * @property string $no_order
 * @property int $no_user
 * @property int|null $no_partner
 * @property int|null $no_shop
 * @property string|null $nm_user
 * @property string|null $ds_car_search
 * @property float|null $at_price_pg
 * @property string|null $cd_mcp_status
 * @property string|null $use_coupon_yn
 * @property Carbon|null $dt_use
 * @property Carbon|null $dt_upt
 * @property string|null $id_upt
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberBenefitCoupon extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_benefit' => 'int',
        'no_user' => 'int',
        'no_partner' => 'int',
        'no_shop' => 'int',
        'at_price_pg' => 'float'
    ];

    protected $dates = [
        'dt_use',
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no_benefit',
        'no_order',
        'no_user',
        'no_partner',
        'no_shop',
        'nm_user',
        'ds_car_search',
        'at_price_pg',
        'cd_mcp_status',
        'use_coupon_yn',
        'dt_use',
        'dt_upt',
        'id_upt',
        'dt_reg'
    ];
}
