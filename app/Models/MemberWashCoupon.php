<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class MemberWashCoupon
 *
 * @property int $no
 * @property int $no_user
 * @property string $no_event
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
 * @property Carbon|null $dt_upt
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberWashCoupon extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

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
        'dt_upt',
        'dt_reg'
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
        'dt_upt',
        'dt_reg'
    ];

    public function washConditions(): HasMany
    {
        return $this->hasMany(MemberWashCouponUseshop::class, 'no_event', 'no_event');
    }
}
