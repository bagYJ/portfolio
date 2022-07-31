<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberInviteRegistLog
 *
 * @property int $no
 * @property int $no_user
 * @property int|null $no_seq
 * @property string|null $ds_invite_fd_no
 * @property int|null $no_user_fd
 * @property string|null $result_code
 * @property string|null $result_msg
 * @property string|null $result_code_last
 * @property string|null $result_msg_last
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberInviteRegistLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_seq' => 'int',
        'no_user_fd' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_seq',
        'ds_invite_fd_no',
        'no_user_fd',
        'result_code',
        'result_msg',
        'result_code_last',
        'result_msg_last',
        'dt_reg'
    ];
}
