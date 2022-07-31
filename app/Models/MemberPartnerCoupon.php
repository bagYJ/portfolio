<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class MemberPartnerCoupon
 *
 * @property int $no
 * @property string $ds_cpn_no_internal
 * @property string $ds_cpn_no
 * @property int $no_user
 * @property int|null $no_partner
 * @property string|null $use_coupon_yn
 * @property string|null $ds_cpn_nm
 * @property string|null $ds_cpn_nm_real
 * @property string|null $use_disc_type
 * @property float|null $at_disct_money
 * @property float|null $at_limit_money
 * @property string|null $cd_payment_card
 * @property string|null $dt_monthly
 * @property int|null $at_condi_liter
 * @property string|null $cd_mcp_status
 * @property string|null $cd_cpe_status
 * @property string|null $no_order
 * @property string|null $cd_payment_status
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $ds_result_code
 * @property Carbon|null $dt_use_start
 * @property Carbon|null $dt_use_end
 * @property int|null $no_event
 * @property Carbon|null $dt_start_from_made
 * @property Carbon|null $dt_end_from_made
 * @property string|null $ds_sn
 * @property string|null $id_admin
 * @property string|null $id_admin_withdraw
 * @property string|null $ds_isssue_code_frm_part
 * @property string|null $yn_is_reused
 * @property string|null $yn_real_pubs
 *
 * @package App\Models
 */
class MemberPartnerCoupon extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;


    protected $casts = [
        'no_user' => 'int',
        'no_partner' => 'int',
        'at_disct_money' => 'float',
        'at_limit_money' => 'float',
        'at_condi_liter' => 'int',
        'no_event' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt',
        'dt_use_start',
        'dt_use_end',
        'dt_start_from_made',
        'dt_end_from_made'
    ];

    protected $fillable = [
        'ds_cpn_no_internal',
        'ds_cpn_no',
        'no_user',
        'no_partner',
        'use_coupon_yn',
        'ds_cpn_nm',
        'ds_cpn_nm_real',
        'use_disc_type',
        'at_disct_money',
        'at_limit_money',
        'cd_payment_card',
        'dt_monthly',
        'at_condi_liter',
        'cd_mcp_status',
        'cd_cpe_status',
        'no_order',
        'cd_payment_status',
        'dt_reg',
        'dt_upt',
        'ds_result_code',
        'dt_use_start',
        'dt_use_end',
        'no_event',
        'dt_start_from_made',
        'dt_end_from_made',
        'ds_sn',
        'id_admin',
        'id_admin_withdraw',
        'ds_isssue_code_frm_part',
        'yn_is_reused',
        'yn_real_pubs'
    ];

    public function washConditions(): HasMany
    {
        return $this->HasMany(MemberWashCouponUseshop::class,);
    }

    public function couponEventCondition(): BelongsTo
    {
        return $this->belongsTo(CouponEventCondition::class, 'no_event', 'no_event');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'no_partner', 'no_partner');
    }

    public function gsCpnEvent(): BelongsTo
    {
        return $this->belongsTo(GsCpnEvent::class, 'no_event', 'no_part_cpn_event');
    }
}
