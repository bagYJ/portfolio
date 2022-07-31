<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CouponEventCondition
 *
 * @property int $no_event
 * @property string $cd_cpn_condi_type
 * @property int|string $ds_target
 *
 * @package App\Models
 */
class CouponEventCondition extends Model
{
    public $incrementing = false;
    public $timestamps = false;


    protected $casts = [
        'no_event' => 'int'
    ];

    protected $fillable = [
        'cd_cpn_condi_type',
        'ds_target'
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'ds_target', 'no_partner');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'ds_target', 'no_shop');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(CodeManage::class, 'ds_target', 'no_code');
    }

    public function partnerCategory(): BelongsTo
    {
        return $this->belongsTo(PartnerCategory::class, 'ds_target', 'no_partner_category');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ds_target', 'no_product');
    }
}
