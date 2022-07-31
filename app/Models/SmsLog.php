<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class SmsLog
 *
 * @property int $seq
 * @property string|null $cd_biz_kind
 * @property string $from_phone
 * @property string $to_ds_phone_number
 * @property int $no_user
 * @property string|null $no_order
 * @property string $message
 * @property string|null $result_code
 * @property string|null $response_code
 * @property string|null $error_msg
 * @property Carbon|null $dt_send
 * @property Carbon $dt_reg
 * @property string|null $id_reg
 *
 * @package App\Models
 */
class SmsLog extends Model
{
    protected $primaryKey = 'seq';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int'
    ];

    protected $dates = [
        'dt_send',
        'dt_reg'
    ];

    protected $fillable = [
        'cd_biz_kind',
        'from_phone',
        'to_ds_phone_number',
        'no_user',
        'no_order',
        'message',
        'result_code',
        'response_code',
        'error_msg',
        'dt_send',
        'dt_reg',
        'id_reg'
    ];
}
