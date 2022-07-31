<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberCouponAuto
 *
 * @property int $no_auto_cpn
 * @property int $no_user
 * @property int $no_auto_cpn_event
 * @property string $cd_mcp_status
 * @property Carbon $dt_reg
 * @property Carbon $dt_expire
 * @property Carbon|null $dt_upt
 * @property string|null $no_order
 * @property string|null $no_order_create
 *
 * @package App\Models
 */
class MemberCouponAuto extends Model
{
    protected $primaryKey = 'no_auto_cpn';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_auto_cpn' => 'int',
        'no_user' => 'int',
        'no_auto_cpn_event' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_expire',
        'dt_upt'
    ];

    protected $fillable = [
        'no_user',
        'no_auto_cpn_event',
        'cd_mcp_status',
        'dt_reg',
        'dt_expire',
        'dt_upt',
        'no_order',
        'no_order_create'
    ];
}
