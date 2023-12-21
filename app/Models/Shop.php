<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class Shop
 * 
 * @property int $no
 * @property int $no_shop
 * @property int|null $no_partner
 * @property string|null $nm_shop
 * @property string|null $ds_tel
 * @property string|null $ds_event_msg
 * @property string|null $ds_open_time
 * @property string|null $ds_close_time
 * @property string|null $ds_status
 * @property float|null $at_grade
 * @property int|null $at_post
 * @property string|null $ds_address
 * @property string|null $ds_address2
 * @property string|null $ds_sido
 * @property string|null $ds_gugun
 * @property string|null $ds_dong
 * @property float|null $at_lat
 * @property float|null $at_lng
 * @property float|null $at_lat_shop
 * @property float|null $at_lng_shop
 * @property string|null $ds_shop_notice
 * @property int|null $ct_view
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $yn_del
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property int|null $at_1_alarm_dst
 * @property int|null $at_2_alarm_dst
 * @property int|null $at_alarm_rssi
 * @property string|null $cd_commission_type
 * @property float|null $at_commission_amount
 * @property float|null $at_commission_rate
 * @property float|null $at_comm_rate_general
 * @property int|null $at_make_ready_time
 * @property float|null $at_min_order
 * @property float|null $at_send_price
 * @property float|null $at_send_disct
 * @property int|null $at_cup_deposit
 * @property string|null $cd_inner_ark_status
 * @property int|null $at_accept_min_rssi
 * @property string|null $cd_pg
 * @property string|null $ds_pg_id
 * @property float|null $at_pg_commission_rate
 * @property string|null $yn_display_map
 * @property string|null $yn_operation
 * @property int|null $no_sales_agency
 * @property float|null $at_sales_commission_rate
 * @property int|null $at_basic_time
 * @property float|null $at_basic_fee
 * @property int|null $at_over_time
 * @property float|null $at_over_fee
 * @property string|null $yn_can_card
 * @property string|null $cd_status_open
 * @property string|null $list_cd_booking_type
 * @property string|null $list_cd_third_party
 * @property string|null $cd_third_party
 * @property string|null $store_cd
 * @property int|null $ct_device_error
 * @property Carbon|null $external_dt_status
 * @property string|null $cd_spc_store
 * @property Partner $partner
 * @property ShopDetail $shopDetail
 * @property ShopHoliday $shopHolidayExists
 * @property ShopOptTime $shopOptTimeExists
 * @property Collection $oilUnits
 *
 * @package App\Models
 */
class Shop extends Model
{
	protected $primaryKey = 'no_shop';
	public $incrementing = false;
	public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = null;

	protected $casts = [];

	protected $fillable = [
        'no_shop',
        'no_partner',
        'nm_shop',
        'ds_tel',
        'ds_open_time',
        'ds_close_time',
        'at_lat',
        'at_lng',
        'ct_view'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'no_partner', 'no_partner');
    }

    public function shopDetail(): HasOne
    {
        return $this->hasOne(ShopDetail::class, 'no_shop', 'no_shop');
    }

    public function shopHolidayExists(): HasOne
    {
        return $this->hasOne(ShopHoliday::class, 'no_shop', 'no_shop')
            ->where('nt_weekday', DB::raw('WEEKDAY(NOW())'))
            ->where(function (Builder $query) {
                $query->where('cd_holiday', '211200')->orWhereRaw(
                    'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 0',
                    ['211300', now()->startOfMonth()->format('Y-m-d')]
                )->orWhereRaw(
                    'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 1',
                    ['211400', now()->startOfMonth()->format('Y-m-d')]
                )->orWhereRaw(
                    'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 2',
                    ['211500', now()->startOfMonth()->format('Y-m-d')]
                )->orWhereRaw(
                    'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 3',
                    ['211600', now()->startOfMonth()->format('Y-m-d')]
                );
            })->orWhereRaw(
                'cd_holiday = ? AND ? BETWEEN dt_imsi_start AND dt_imsi_end',
                ['211900', now()]
            );
    }

    public function shopOptTimeExists(): HasOne
    {
        $nowWeek = now()->dayOfWeek - 1 < 0 ? 6 : now()->dayOfWeek - 1;

        return $this->hasOne(ShopOptTime::class, 'no_shop', 'no_shop')
            ->where('nt_weekday', $nowWeek)
            ->where(function (Builder $query) {
                $query->whereBetween(
                    DB::raw(now()->format('Hi')),
                    [DB::raw('ds_break_start_time'), DB::raw('ds_break_end_time')]
                )
                    ->orWhereBetween(
                        DB::raw(now()->format('Hi')),
                        [DB::raw('ds_break_start_time2'), DB::raw('ds_break_end_time2')]
                    )
                    ->orWhereNotBetween(
                        DB::raw(now()->format('Hi')),
                        [DB::raw('ds_open_time'), DB::raw('ds_close_time')]
                    );
            });
    }

    public function oilUnits(): HasMany
    {
        return $this->hasMany(ShopOilUnit::class, 'no_shop', 'no_shop');
    }
}
