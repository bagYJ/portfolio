<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Class OrderList
 * 
 * @property int $no
 * @property string $no_order
 * @property int|null $no_payment_last
 * @property string $nm_order
 * @property string $no_user
 * @property int|null $no_device
 * @property string|null $ds_adver
 * @property int $no_partner
 * @property int $no_shop
 * @property string $cd_service
 * @property int|null $cd_service_pay
 * @property string $cd_order_status
 * @property string $cd_pickup_status
 * @property string|null $cd_pg
 * @property string $cd_payment
 * @property string|null $cd_payment_kind
 * @property string|null $cd_payment_method
 * @property string|null $cd_payment_status
 * @property int|null $no_card
 * @property float $at_price
 * @property float|null $at_price_event
 * @property float|null $at_cash_before
 * @property float|null $at_cash_after
 * @property float|null $at_eventcash_before
 * @property float|null $at_eventcash_after
 * @property float|null $at_lat_decide
 * @property float|null $at_lng_decide
 * @property float|null $at_distance
 * @property string|null $ds_address
 * @property string|null $ds_address2
 * @property Carbon|null $dt_pickup
 * @property Carbon|null $dt_pickup_status
 * @property Carbon|null $dt_order_status
 * @property Carbon|null $dt_payment_status
 * @property Carbon|null $dt_reg
 * @property string|null $ds_pg_id
 * @property string|null $cd_alarm_event_type
 * @property string|null $yn_gps_status
 * @property float|null $at_commission_rate
 * @property string|null $cd_calc_status
 * @property string|null $cd_send_status
 * @property int|null $no_send
 * @property string|null $cd_call_shop
 * @property int|null $at_add_delay_min
 * @property float|null $at_event_support
 * @property Carbon|null $dt_check_cancel
 * @property int|null $at_disct
 * @property int|null $at_cpn_disct
 * @property int|null $at_point_disct
 * @property int|null $at_cash_disct
 * @property int|null $at_event_cash_disct
 * @property int|null $at_bank_disct
 * @property string|null $cd_gas_kind
 * @property float|null $at_gas_price
 * @property float|null $at_gas_price_opnet
 * @property float|null $at_price_pg
 * @property string|null $yn_cash_receipt
 * @property string|null $yn_gas_order_liter
 * @property float|null $at_price_real_gas
 * @property float|null $at_liter_gas
 * @property float|null $at_liter_real
 * @property string|null $ds_unit_id
 * @property string|null $no_approval
 * @property string|null $dt_approval
 * @property string|null $yn_gas_pre_order
 * @property string|null $id_pointcard
 * @property int|null $at_p_point_for_add
 * @property string|null $ds_request_msg
 * @property string|null $ds_safe_number
 * @property string|null $ds_cpn_no
 * @property string|null $yn_confirm
 * @property string|null $ds_franchise_num
 * @property string|null $ds_request_msg_2
 * @property int|null $seq
 * @property string|null $ds_car_number
 * @property string|null $cd_booking_type
 * @property string|null $cd_pickup_type
 * @property string|null $cd_send_type
 * @property float|null $at_send_price
 * @property float|null $at_send_disct
 * @property string|null $no_reject_product_list
 * @property string|null $cd_third_party
 * @property string|null $cd_order_adm_chk
 * @property Carbon|null $confirm_date
 * @property Carbon|null $ready_date
 * @property Carbon|null $pickup_date
 * @property string|null $ds_spc_order
 * @property Collection|RetailOrderProduct[] $retailOrderProduct
 * @property Member $member
 * @property Shop $shop
 * @property ShopOil $shopOil
 * @property Partner $partner
 * @property Collection $card
 * @property ?MemberCoupon $coupon
 * @property OrderPayment $orderPayment
 *
 * @package App\Models
 */
class OrderList extends Model
{
	protected $primaryKey = 'no_order';
    protected $keyType = 'string';
	public $incrementing = false;
	public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = null;
    public const DELETED_AT = null;

	protected $casts = [
        'dt_reg' => 'datetime',
        'dt_pickup' => 'datetime'
    ];

	protected $fillable = [
        'no',
        'no_order',
        'no_payment_last',
        'nm_order',
        'no_user',
        'no_device',
        'ds_adver',
        'no_partner',
        'no_shop',
        'cd_service',
        'cd_service_pay',
        'cd_order_status',
        'cd_pickup_status',
        'cd_pg',
        'cd_payment',
        'cd_payment_kind',
        'cd_payment_method',
        'cd_payment_status',
        'no_card',
        'at_price',
        'at_price_event',
        'at_cash_before',
        'at_cash_after',
        'at_eventcash_before',
        'at_eventcash_after',
        'at_lat_decide',
        'at_lng_decide',
        'at_distance',
        'dt_pickup',
        'ds_address',
        'ds_address2',
        'dt_pickup_status',
        'dt_order_status',
        'dt_payment_status',
        'dt_reg',
        'ds_pg_id',
        'cd_alarm_event_type',
        'yn_gps_status',
        'at_commission_rate',
        'cd_calc_status',
        'cd_send_status',
        'no_send',
        'cd_call_shop',
        'at_add_delay_min',
        'at_event_support',
        'dt_check_cancel',
        'at_disct',
        'at_cpn_disct',
        'at_point_disct',
        'at_cash_disct',
        'at_event_cash_disct',
        'at_bank_disct',
        'cd_gas_kind',
        'at_gas_price',
        'at_gas_price_opnet',
        'at_price_pg',
        'yn_cash_receipt',
        'yn_gas_order_liter',
        'at_price_real_gas',
        'at_liter_gas',
        'at_liter_real',
        'ds_unit_id',
        'no_approval',
        'dt_approval',
        'yn_gas_pre_order',
        'id_pointcard',
        'at_p_point_for_add',
        'ds_request_msg',
        'ds_safe_number',
        'ds_cpn_no',
        'yn_confirm',
        'ds_franchise_num',
        'ds_request_msg_2',
        'seq',
        'ds_car_number',
        'cd_booking_type',
        'cd_send_type',
        'at_send_price',
        'no_reject_product_list',
        'cd_third_party',
        'cd_order_adm_chk',
        'confirm_date',
        'ready_date',
        'pickup_date',
        'cd_pickup_type',
        'ds_spc_order'
    ];

    public function retailOrderProduct(): HasMany
    {
        return $this->hasMany(RetailOrderProduct::class, 'no_order', 'no_order');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'no_user', 'no_user');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'no_shop', 'no_shop');
    }

    public function shopOil(): BelongsTo
    {
        return $this->belongsTo(ShopOil::class, 'no_shop', 'no_shop');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'no_partner', 'no_partner');
    }

    public function orderProduct(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'no_order', 'no_order');
    }

    public function card(): belongsTo
    {
        return $this->belongsTo(MemberCard::class, 'no_card', 'no_card');
    }

    public function coupon(): belongsTo
    {
        return $this->belongsTo(MemberCoupon::class, 'no_order', 'no_order');
    }

    public static function getOrderInfo(string $noOrder): self
    {
        return self::find($noOrder);
    }

    public function orderPayment(): HasOne
    {
        return $this->hasOne(OrderPayment::class, 'no_order', 'no_order');
    }
}
