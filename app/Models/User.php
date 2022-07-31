<?php

namespace App\Models;

use App\Traits\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

//use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $ds_passwd
 * @property string $ds_passwd_api
 * @property string $id_user
 * @property string $nm_user
 * @property string $nm_nick
 * @property string $cd_reg_kind
 * @property string $ds_status
 * @property string $ds_phone
 * @property string $ds_ci
 * @property string $ds_birthday
 * @property string $ds_sex
 * @property Carbon $dt_reg
 * @property int $no_user
 * @property int $cd_mem_level // 테이블상 string으로 되어있는데 변경 가능한지 확인 요망
 * @property MemberDetail $memberDetail
 * @property Collection $memberCard
 *
 * @property string $cd_third_party
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';

    protected $table = 'member';

    protected $primaryKey = 'no_user';

    public $incrementing = false;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nm_nick',
        'yn_owin_member',
        'ds_social',
        'no_user',
        'id_user',
        'ds_passwd_api',
        'ds_status',
        'ds_birthday',
        'ds_sex',
        'ds_phone',
        'ds_di',
        'ds_ci',
        'nm_user',
        'cd_mem_level',
        'yn_push_msg_mobile'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthPassword(): ?string
    {
        return $this->ds_passwd_api;
    }

    public function findForPassport($username): self
    {
        return $this->where('id_user', $username)->first();
    }

    public function oauthAccessTokens(): HasMany
    {
        return $this->hasMany(OauthAccessTokens::class, 'user_id');
    }

    public function memberDetail(): HasOne
    {
        return $this->hasOne(MemberDetail::class, 'no_user');
    }

    public function beaconCount(): HasMany
    {
        return $this->hasMany(Beacon::class, 'no_user')
            ->where('cd_device_status', '=', '303100');
    }

    public function beacon(): HasOne
    {
        return $this->hasOne(Beacon::class, 'no_user')
            ->where('cd_device_status', '=', '303100')
            ->select(
                'no_user',
                DB::raw(
                    '
            '
                )
            );
    }

    public function memberCarInfo(): HasOne
    {
        return $this->hasOne(MemberCarinfo::class, 'no_user', 'no_user')
            ->where('yn_main_car', '=', 'Y');
    }

    public function memberCarInfoAll(): HasMany
    {
        return $this->hasMany(MemberCarinfo::class, 'no_user', 'no_user')->with(['carList'])->orderByDesc('yn_main_car');
    }

    public function memberCard(): HasMany
    {
        return $this->hasMany(MemberCard::class, 'no_user')->orderByDesc('yn_main_card');
    }

    public function memberFavorMap(): HasMany
    {
        return $this->hasMany(MemberFavorMap::class, 'no_user');
    }

    public function memberApt(): HasOne
    {
        return $this->hasOne(MemberApt::class, 'no_user')
            ->orderByDesc('no');
    }

    public function memberCoupon(): HasMany
    {
        return $this->hasMany(MemberCoupon::class, 'no_user');
    }

    public function memberPointCard(): HasMany
    {
        return $this->hasMany(MemberPointcard::class, 'no_user', 'no_user')
            ->where('yn_delete', '=', 'N')->with(['promotionDeal', 'gsSaleCard']);
    }

    public function orderList(): HasMany
    {
        return $this->hasMany(OrderList::class, 'no_user', 'no_user');
    }

}
