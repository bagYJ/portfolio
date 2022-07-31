<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Class MemberCoupon
 *
 * @property int $no_user
 * @property int $no_event
 * @property string|null $nm_event
 * @property string $cd_mcp_status
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $no_order
 * @property string|null $ds_etc
 * @property string|null $id_admin
 * @property int|string|null $at_discount
 * @property string|null $cd_disc_type
 * @property string $coupon_type
 * @property string $discount_type
 * @property string|null $available_partner
 * @property string|null $available_shop
 * @property string|null $available_card
 * @property string|null $available_weekday
 * @property string|null $available_category
 * @property string|null $available_product
 * @property string|null $at_price_limit
 * @property string $yn_condi_status_partner
 * @property string $yn_condi_status_shop
 * @property string $yn_condi_status_weekday
 * @property string $yn_condi_status_category
 * @property string $yn_condi_status_menu
 * @property string $yn_condi_status_money
 * @property float|null $at_max_disc
 * @property CouponEvent $couponEvent
 *
 * @package App\Models
 */
class MemberCoupon extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_event' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'cd_mcp_status',
        'dt_reg',
        'dt_upt',
        'no_order',
        'ds_etc',
        'id_admin'
    ];

    public function couponEvent(): HasOne
    {
        return $this->hasOne(CouponEvent::class, 'no_event', 'no_event');
    }

    public function couponEventProduct(): HasOneThrough
    {
        return $this->hasOneThrough(
            Product::class,
            CouponEvent::class,
            'no_event',
            'no_product',
            'no_event',
            'at_discount'
        )
            ->where('cd_disc_type', '=', '126300');
    }

//    public function couponEventCondition(): HasManyThrough
//    {
//        return $this->hasManyThrough(CouponEventCondition::class, CouponEvent::class, 'no_event', 'no_event', 'no_event', 'no_event');
//    }

    public function couponEventCondition(): HasMany
    {
        return $this->hasMany(CouponEventCondition::class, 'no_event', 'no_event');
    }

}
