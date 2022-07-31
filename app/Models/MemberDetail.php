<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\EnumYN;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * Class MemberDetail
 *
 * @property int $no_user
 * @property string|null $ds_nation
 * @property string|null $ds_udid
 * @property string|null $ds_profile_path
 * @property string|null $ds_phone_agency
 * @property string|null $ds_phone_model
 * @property string|null $ds_phone_nation
 * @property string|null $ds_phone_token
 * @property string|null $ds_phone_token_real
 * @property string|null $cd_phone_os
 * @property string|null $ds_phone_version
 * @property string|null $yn_email_agree
 * @property Carbon|null $dt_last_login
 * @property string|null $ds_last_login_ip
 * @property int|null $no_withdrawal
 * @property string|null $ds_withdrawal
 * @property Carbon|null $dt_withdrawal
 * @property string|null $yn_account_status
 * @property string|null $ds_third_party_id
 * @property string|null $ds_carid_hk
 * @property string|null $cd_third_party
 * @property string|null $nm_third_party
 * @property Carbon|null $dt_account_reg
 * @property Carbon|null $dt_account_del_reg
 * @property string|null $ds_access_token_rsm
 * @property string|null $ds_access_vin_rsm
 * @property string|null $yn_account_status_rsm
 * @property Carbon|null $dt_account_reg_rsm
 * @property string|null $ds_access_token
 *
 * @package App\Models
 */
class MemberDetail extends Model
{
    protected $primaryKey = 'no_user';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no_user' => 'int',
        'no_withdrawal' => 'int'
    ];

    protected $dates = [
        'dt_last_login',
        'dt_withdrawal',
        'dt_account_reg',
        'dt_account_del_reg',
        'dt_account_reg_rsm'
    ];

    protected $hidden = [
        'ds_phone_token',
        'ds_access_token'
    ];

    protected $fillable = [
        'ds_nation',
        'ds_udid',
        'ds_profile_path',
        'ds_phone_agency',
        'ds_phone_model',
        'ds_phone_nation',
        'ds_phone_token',
        'ds_phone_token_real',
        'cd_phone_os',
        'ds_phone_version',
        'yn_email_agree',
        'dt_last_login',
        'ds_last_login_ip',
        'no_withdrawal',
        'ds_withdrawal',
        'dt_withdrawal',
        'yn_account_status',
        'ds_third_party_id',
        'ds_carid_hk',
        'cd_third_party',
        'nm_third_party',
        'dt_account_reg',
        'dt_account_del_reg',
        'ds_access_token_rsm',
        'ds_access_token_api',
        'ds_access_vin_rsm',
        'yn_account_status_rsm',
        'dt_account_reg_rsm',
        'ds_access_token',
        'no_user'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'no_user');
    }

    public function beacon(): HasOne
    {
        return $this->hasOne(Beacon::class, 'no_user')
            ->where('cd_device_status', '=', '303100')
            ->select('no_user', DB::raw('GROUP_CONCAT(id_beacon) AS id_beacons'), DB::raw('COUNT(*) as cnt'));
    }

    public function memberCarInfo(): HasOne
    {
        return $this->hasOne(MemberCarinfo::class, 'no_user', 'no_user')
            ->where('yn_main_car', EnumYN::Y->name)
            ->where('yn_delete', EnumYN::N->name)
            ->orderByDesc('dt_reg');
    }

    // 특정필드만 가져올수있는지 확인
    public function memberCard(): HasMany
    {
        return $this->hasMany(MemberCard::class, 'no_user')
            ->where('yn_delete', '=', EnumYN::N->name)
            ->select(
                'no_card',
                'no_card_user',
                'cd_card_corp',
                'cd_pg',
                'dt_reg',
                'ds_billkey',
                DB::raw('504100 as cd_payment_method')
            );
    }

    public function memberCardList(): HasMany
    {
        return $this->memberCard()
            ->orderByDesc('cd_payment_method')
            ->orderByDesc('dt_reg');
    }
}
