<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberInviteHistory
 *
 * @property int $no
 * @property int $no_user
 * @property int|null $no_seq
 * @property int|null $no_user_fd
 * @property string|null $ds_invite_fd_no
 * @property string|null $yn_st_order
 * @property Carbon|null $dt_st_order
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class MemberInviteHistory extends Model
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
        'dt_st_order',
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_seq',
        'no_user_fd',
        'ds_invite_fd_no',
        'yn_st_order',
        'dt_st_order',
        'dt_reg'
    ];
}
