<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class MemberInviteCode
 *
 * @property int $no
 * @property int $no_user
 * @property string|null $ds_invite_no
 * @property Carbon|null $dt_invite_reg
 * @property int|null $ct_invite
 * @property int|null $ct_order
 * @property string|null $ds_invite_fd_no
 * @property Carbon|null $dt_invite_fd_reg
 * @property string|null $yn_invite_fd_coupon
 * @property Carbon|null $dt_invite_fd_coupon
 *
 * @package App\Models
 */
class MemberInviteCode extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = false;

    protected $casts = [
        'no_user' => 'int',
        'ct_invite' => 'int',
        'ct_order' => 'int'
    ];

    protected $dates = [
        'dt_invite_reg',
        'dt_invite_fd_reg',
        'dt_invite_fd_coupon'
    ];

    protected $fillable = [
        'no_user',
        'ds_invite_no',
        'dt_invite_reg',
        'ct_invite',
        'ct_order',
        'ds_invite_fd_no',
        'dt_invite_fd_reg',
        'yn_invite_fd_coupon',
        'dt_invite_fd_coupon'
    ];
}
