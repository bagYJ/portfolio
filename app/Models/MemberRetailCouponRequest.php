<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberRetailCouponRequest
 *
 * @property int $no
 * @property int|null $no_user
 * @property string|null $no_coupon
 * @property string|null $no_event
 * @property string|null $nm_event
 * @property string|null $use_coupon_yn
 * @property string|null $cd_mcp_status
 * @property float|null $at_disct_money
 * @property int|null $at_expire_day
 * @property Carbon|null $dt_use_start
 * @property Carbon|null $dt_use_end
 * @property int|null $at_min_price
 * @property string|null $cd_issue_kind
 * @property string|null $cd_calculate_main
 * @property string|null $user_type
 * @property string|null $id_admin_issue
 * @property Carbon|null $dt_use
 * @property string|null $no_order_retail
 * @property string|null $id_admin_withdraw
 * @property string|null $list_usepartner
 * @property string|null $yn_success
 * @property string|null $error_msg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberRetailCouponRequest extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'at_disct_money' => 'float',
        'at_expire_day' => 'int',
        'at_min_price' => 'int'
    ];

    protected $dates = [
        'dt_use_start',
        'dt_use_end',
        'dt_use',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_coupon',
        'no_event',
        'nm_event',
        'use_coupon_yn',
        'cd_mcp_status',
        'at_disct_money',
        'at_expire_day',
        'dt_use_start',
        'dt_use_end',
        'at_min_price',
        'cd_issue_kind',
        'cd_calculate_main',
        'user_type',
        'id_admin_issue',
        'dt_use',
        'no_order_retail',
        'id_admin_withdraw',
        'list_usepartner',
        'yn_success',
        'error_msg',
        'dt_reg'
    ];
}
