<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberWashCouponRequest
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $no_event
 * @property string|null $nm_event
 * @property string|null $use_coupon_yn
 * @property string|null $cd_mcp_status
 * @property float|null $at_disct_money
 * @property int|null $at_expire_day
 * @property Carbon|null $dt_use_start
 * @property Carbon|null $dt_use_end
 * @property string|null $cd_issue_kind
 * @property string|null $cd_calculate_main
 * @property string|null $no_order_oil
 * @property float|null $at_price
 * @property string|null $id_admin_issue
 * @property Carbon|null $dt_use
 * @property string|null $no_order_wash
 * @property string|null $id_admin_withdraw
 * @property string|null $list_useshop
 * @property string|null $yn_success
 * @property string|null $reg_msg
 * @property string|null $reg_code
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_del
 *
 * @package App\Models
 */
class MemberWashCouponRequest extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_user' => 'int',
        'at_disct_money' => 'float',
        'at_expire_day' => 'int',
        'at_price' => 'float'
    ];

    protected $dates = [
        'dt_use_start',
        'dt_use_end',
        'dt_use',
        'dt_reg',
        'dt_del'
    ];

    protected $fillable = [
        'no_user',
        'no_event',
        'nm_event',
        'use_coupon_yn',
        'cd_mcp_status',
        'at_disct_money',
        'at_expire_day',
        'dt_use_start',
        'dt_use_end',
        'cd_issue_kind',
        'cd_calculate_main',
        'no_order_oil',
        'at_price',
        'id_admin_issue',
        'dt_use',
        'no_order_wash',
        'id_admin_withdraw',
        'list_useshop',
        'yn_success',
        'reg_msg',
        'reg_code',
        'dt_reg',
        'dt_del'
    ];
}
