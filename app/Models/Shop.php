<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\AppType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
 * @property string|null $cd_inner_ark_status
 * @property int|null $at_accept_min_rssi
 * @property string|int|null $cd_pg
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
 *
 * @property string $nm_partner
 * @property string $cd_biz_kind
 * @property string $ds_pin
 * @property string $ds_menu_info
 * @property string $yn_open
 * @property string $biz_kind
 * @property Shop $wash_shop
 * @property Collection $list_category
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
    public const DELETED_AT = 'dt_del';

    protected $casts = [
        'no' => 'int',
        'no_shop' => 'int',
        'no_partner' => 'int',
        'at_grade' => 'float',
        'at_post' => 'int',
        'at_lat' => 'float',
        'at_lng' => 'float',
        'at_lat_shop' => 'float',
        'at_lng_shop' => 'float',
        'ct_view' => 'int',
        'at_1_alarm_dst' => 'int',
        'at_2_alarm_dst' => 'int',
        'at_alarm_rssi' => 'int',
        'at_commission_amount' => 'float',
        'at_commission_rate' => 'float',
        'at_comm_rate_general' => 'float',
        'at_make_ready_time' => 'int',
        'at_min_order' => 'float',
        'at_send_price' => 'float',
        'at_accept_min_rssi' => 'int',
        'at_pg_commission_rate' => 'float',
        'no_sales_agency' => 'int',
        'at_sales_commission_rate' => 'float',
        'at_basic_time' => 'int',
        'at_basic_fee' => 'float',
        'at_over_time' => 'int',
        'at_over_fee' => 'float',
        'ct_device_error' => 'int',
        'cd_pg' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg',
        'external_dt_status'
    ];

    protected $fillable = [
        'no',
        'no_partner',
        'nm_shop',
        'ds_tel',
        'ds_event_msg',
        'ds_open_time',
        'ds_close_time',
        'ds_status',
        'at_grade',
        'at_post',
        'ds_address',
        'ds_address2',
        'ds_sido',
        'ds_gugun',
        'ds_dong',
        'at_lat',
        'at_lng',
        'at_lat_shop',
        'at_lng_shop',
        'ds_shop_notice',
        'ct_view',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'at_1_alarm_dst',
        'at_2_alarm_dst',
        'at_alarm_rssi',
        'cd_commission_type',
        'at_commission_amount',
        'at_commission_rate',
        'at_comm_rate_general',
        'at_make_ready_time',
        'at_min_order',
        'at_send_price',
        'cd_inner_ark_status',
        'at_accept_min_rssi',
        'cd_pg',
        'ds_pg_id',
        'at_pg_commission_rate',
        'yn_display_map',
        'yn_operation',
        'no_sales_agency',
        'at_sales_commission_rate',
        'at_basic_time',
        'at_basic_fee',
        'at_over_time',
        'at_over_fee',
        'yn_can_card',
        'cd_status_open',
        'list_cd_booking_type',
        'list_cd_third_party',
        'cd_third_party',
        'store_cd',
        'ct_device_error',
        'external_dt_status'
    ];

    public function shopDetail(): HasOne
    {
        return $this->hasOne(ShopDetail::class, 'no_shop', 'no_shop');
    }

    public function shopOptTime(): HasMany
    {
        return $this->hasMany(ShopOptTime::class, 'no_shop', 'no_shop');
    }

    public function shopHoliday(): HasMany
    {
        return $this->hasMany(ShopHoliday::class, 'no_shop', 'no_shop');
    }

    public function shopHolidayExists(): HasOne
    {
        return $this->hasOne(ShopHoliday::class, 'no_shop', 'no_shop')
            ->where('nt_weekday', DB::raw('WEEKDAY(NOW())'))
            ->where(function ($query) {
                $query->where('cd_holiday', '211200')
                    ->orWhereRaw(
                        'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 0',
                        ['211300', now()->startOfMonth()->format('Y-m-d')]
                    )
                    ->orWhereRaw(
                        'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 1',
                        ['211400', now()->startOfMonth()->format('Y-m-d')]
                    )
                    ->orWhereRaw(
                        'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 2',
                        ['211500', now()->startOfMonth()->format('Y-m-d')]
                    )
                    ->orWhereRaw(
                        'cd_holiday = ? AND WEEKOFYEAR(NOW()) - WEEKOFYEAR(?) = 3',
                        ['211600', now()->startOfMonth()->format('Y-m-d')]
                    );
            });
    }

    public function shopOptTimeExists(): HasOne
    {
        $nowWeek = now()->dayOfWeek - 1 < 0 ? 6 : now()->dayOfWeek - 1;

        return $this->hasOne(ShopOptTime::class, 'no_shop', 'no_shop')
            ->where('nt_weekday', $nowWeek)
            ->where(function ($query) {
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
                    )
                    ->orWhere(function ($query) {
                        $query->whereNotNull('ds_open_order_time')
                            ->whereNotNull('ds_close_order_time')
                            ->whereNotBetween(
                                DB::raw(now()->format('Hi')),
                                [DB::raw('ds_open_order_time'), DB::raw('ds_close_order_time')]
                            );
                    });
            });
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'no_partner', 'no_partner');
    }

    public function washInshop(): HasOne //세차장 정보
    {
        return $this->hasOne(WashInshop::class, 'no_shop', 'no_shop');
    }

    public function oilInShop(): HasOne //주차장 정보
    {
        return $this->hasOne(WashInshop::class, 'no_shop', 'no_shop');
    }

    public function shopOil(): HasOne
    {
        return $this->hasOne(ShopOil::class, 'no_shop', 'no_shop')->orderByDesc('dt_mofy');
    }

    public function shopOilPrice(): HasMany
    {
        return $this->hasMany(ShopOilPrice::class, 'no_shop', 'no_shop');
    }

    public function shopOilUnUseCard(): HasMany
    {
        return $this->hasMany(ShopOilUnuseCard::class, 'no_shop', 'no_shop');
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'no_partner', 'no_partner');
    }

    public function partnerCategory(): HasMany
    {
        return $this->hasMany(PartnerCategory::class, 'no_partner', 'no_partner')
            ->orderBy('ct_order');
    }

    public function retailCategory(): HasMany
    {
        return $this->hasMany(RetailCategory::class, 'no_partner', 'no_partner')
            ->whereBetween(DB::raw('NOW()'), [DB::raw('dt_use_st'), DB::raw('dt_use_end')])
            ->where([
                'yn_show' => 'Y',
                (getAppType() == AppType::AVN ? 'ds_avn_status' : 'ds_status') => 'Y'
            ])->orderByDesc('yn_top')->orderBy('at_view');
    }

    public function washProducts(): HasMany
    {
        return $this->hasMany(WashProduct::class, 'no_shop', 'no_shop');
    }
}
