<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Class Member
 * 
 * @property int $no
 * @property string $no_user
 * @property string|null $ds_phone
 * @property string $id_user
 * @property string|null $id_beacon
 * @property string|null $ds_passwd
 * @property string|null $ds_passwd_api
 * @property string|null $ds_social
 * @property string|null $ds_ci
 * @property string|null $ds_di
 * @property string|null $cd_reg_kind
 * @property string|null $cd_reg_service
 * @property string|null $cd_auth_type
 * @property string|null $cd_mem_level
 * @property string $cd_mem_type
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
 * @property MemberDetail $detail
 * @property Collection $card
 *
 * @package App\Models
 */
class Member extends Model
{
	protected $primaryKey = 'no_user';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [];

	protected $fillable = [];

    public function detail(): HasOne
    {
        return $this->hasOne(MemberDetail::class, 'no_user', 'no_user');
    }

    public function card(): HasMany
    {
        return $this->hasMany(MemberCard::class, 'no_user', 'no_user');
    }
}
