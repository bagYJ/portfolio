<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MemberParkingCoupon
 *
 * @property int $no
 * @property string $no_coupon
 * @property int $no_user
 * @property int $no_event
 * @property string $nm_event
 * @property string|null $no_order
 * @property int|null $at_price
 * @property string $use_coupon_yn
 * @property string $cd_mcp_status
 * @property array|null $no_sites
 * @property string $cd_disct_type
 * @property int $at_disct_money
 * @property float $at_disc_rate
 * @property Carbon|null $dt_use_start
 * @property Carbon|null $dt_use_end
 * @property int|null $at_expire_day
 * @property string $cd_issue_kind
 * @property string $cd_calculate_main
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * @property OrderList $orderList
 *
 * @package App\Models
 */
class MemberParkingCoupon extends Model
{
    protected $table = 'member_parking_coupon';
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_event' => 'int',
        'at_price' => 'int',
        'no_sites' => 'json',
        'at_disct_money' => 'int',
        'at_disc_rate' => 'float',
        'at_expire_day' => 'int'
    ];

    protected $dates = [
        'dt_use_start',
        'dt_use_end',
        'dt_use',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_user',
        'no_coupon',
        'no_event',
        'nm_event',
        'no_order',
        'at_price',
        'use_coupon_yn',
        'cd_mcp_status',
        'no_sites',
        'cd_disct_type',
        'at_disct_money',
        'at_disc_rate',
        'dt_use_start',
        'dt_use_end',
        'at_expire_day',
        'cd_issue_kind',
        'cd_calculate_main',
        'dt_reg',
        'dt_upt'
    ];

    public function orderList(): BelongsTo
    {
        return $this->belongsTo(ParkingOrderList::class, 'no_order');
    }
}
