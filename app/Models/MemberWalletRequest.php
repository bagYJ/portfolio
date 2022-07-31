<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberWalletRequest
 *
 * @property int $no
 * @property string $no_user
 * @property string $tr_id
 * @property string|null $trans_dt
 * @property string|null $partner_code
 * @property string|null $otp_token
 * @property string|null $method
 * @property string|null $m_num
 * @property string|null $b_day
 * @property string|null $sex
 * @property string|null $m_name
 * @property string|null $sign
 * @property string|null $nice_cid
 * @property string|null $card_comp_code
 * @property string|null $trans_type
 * @property string|null $card_type
 * @property string|null $offline_use_yn
 * @property string|null $result_code
 * @property string|null $result_msg
 * @property string|null $nm_method
 * @property string|null $terms_tf
 * @property string|null $terms_type
 * @property string|null $cd_card_regist
 * @property string|null $signature
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_request
 * @property Carbon|null $dt_result
 *
 * @package App\Models
 */
class MemberWalletRequest extends Model
{
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;


    protected $dates = [
        'dt_reg',
        'dt_request',
        'dt_result'
    ];

    protected $hidden = [
        'otp_token'
    ];

    protected $fillable = [
        'trans_dt',
        'partner_code',
        'otp_token',
        'method',
        'm_num',
        'b_day',
        'sex',
        'm_name',
        'sign',
        'nice_cid',
        'card_comp_code',
        'trans_type',
        'card_type',
        'offline_use_yn',
        'result_code',
        'result_msg',
        'nm_method',
        'terms_tf',
        'terms_type',
        'cd_card_regist',
        'signature',
        'dt_reg',
        'dt_request',
        'dt_result'
    ];
}
