<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberPartnerCouponRequest
 *
 * @property int $no
 * @property int|null $no_partner
 * @property string|null $no_order
 * @property int|null $no_user
 * @property string|null $ds_cpn_no_internal
 * @property string|null $ds_cpn_no
 * @property string|null $ds_cpn_nm_real
 * @property float|null $at_disct_money
 * @property Carbon|null $dt_start_from_made
 * @property Carbon|null $dt_end_from_made
 * @property string|null $ds_isssue_code_frm_part
 * @property string|null $yn_success
 * @property string|null $reg_msg
 * @property string|null $reg_code
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_del
 *
 * @package App\Models
 */
class MemberPartnerCouponRequest extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no_partner' => 'int',
        'no_user' => 'int',
        'at_disct_money' => 'float'
    ];

    protected $dates = [
        'dt_start_from_made',
        'dt_end_from_made',
        'dt_reg',
        'dt_del'
    ];

    protected $fillable = [
        'no_partner',
        'no_order',
        'no_user',
        'ds_cpn_no_internal',
        'ds_cpn_no',
        'ds_cpn_nm_real',
        'at_disct_money',
        'dt_start_from_made',
        'dt_end_from_made',
        'ds_isssue_code_frm_part',
        'yn_success',
        'reg_msg',
        'reg_code',
        'dt_reg',
        'dt_del'
    ];
}
