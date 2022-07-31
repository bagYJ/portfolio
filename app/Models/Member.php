<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Member
 *
 * @property int $no
 * @property int $no_user
 * @property string|null $ds_phone
 * @property string $id_user
 * @property string|null $id_beacon
 * @property string|null $ds_passwd
 * @property string|null $ds_social
 * @property string|null $ds_ci
 * @property string|null $ds_di
 * @property string|null $cd_reg_kind
 * @property string|null $cd_reg_service
 * @property string|null $cd_auth_type
 * @property string|null $cd_mem_level
 * @property string|null $nm_user
 * @property string|null $nm_nick
 * @property string|null $yn_push_msg
 * @property string|null $yn_push_msg_event
 * @property string|null $yn_push_msg_mobile
 * @property string|null $ds_birthday
 * @property string|null $ds_sex
 * @property float|null $at_cash
 * @property float|null $at_event_cash
 * @property string $ds_status
 * @property Carbon|null $dt_upt
 * @property Carbon $dt_reg
 * @property string|null $yn_owin_member
 * @property string|null $cd_booking_type
 *
 * @package App\Models
 */
class Member extends Model
{
    protected $primaryKey = 'no_user';
    public $incrementing = false;
    public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_user' => 'int',
        'at_cash' => 'float',
        'at_event_cash' => 'float'
    ];

    protected $dates = [
        'dt_upt',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'ds_phone',
        'id_user',
        'id_beacon',
        'ds_passwd',
        'ds_social',
        'ds_ci',
        'ds_di',
        'cd_reg_kind',
        'cd_reg_service',
        'cd_auth_type',
        'cd_mem_level',
        'nm_user',
        'nm_nick',
        'yn_push_msg',
        'yn_push_msg_event',
        'yn_push_msg_mobile',
        'ds_birthday',
        'ds_sex',
        'at_cash',
        'at_event_cash',
        'ds_status',
        'dt_upt',
        'dt_reg',
        'yn_owin_member',
        'cd_booking_type'
    ];

    public function memberDetail(): BelongsTo
    {
        return $this->belongsTo(MemberDetail::class, 'no_user', 'no_user');
    }
}
