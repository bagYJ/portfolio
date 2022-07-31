<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberWalletWithdrawal
 *
 * @property int $no
 * @property int $no_user
 * @property string $tr_id
 * @property string|null $trans_dt
 * @property string|null $partner_code
 * @property string|null $otp_token
 * @property string|null $method
 * @property string|null $sign
 * @property string|null $result_code
 * @property string|null $result_msg
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_result
 *
 * @package App\Models
 */
class MemberWalletWithdrawal extends Model
{
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_reg',
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
        'sign',
        'result_code',
        'result_msg',
        'dt_reg',
        'dt_result'
    ];
}
