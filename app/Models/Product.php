<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Product
 *
 * @property int $no
 * @property int $no_product
 * @property int|null $no_partner
 * @property string|null $ds_option_sel
 * @property string|null $nm_product
 * @property string|null $ds_content
 * @property int|null $no_partner_category
 * @property float|null $at_price_before
 * @property float|null $at_price
 * @property float|null $at_price_us
 * @property float|null $at_commission
 * @property string|null $ds_image_path
 * @property string|null $ds_recommend_start_time
 * @property string|null $ds_recommend_end_time
 * @property int|null $no_sel_group1
 * @property int|null $no_sel_group2
 * @property int|null $no_sel_group3
 * @property int|null $no_sel_group4
 * @property int|null $no_sel_group5
 * @property string|null $yn_new
 * @property string|null $yn_vote
 * @property string|null $ds_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property int|null $at_view_order
 * @property string|null $cd_gas_kind
 * @property string|null $cd_car_kind
 * @property float|int $at_ratio
 * @property string $option_group
 *
 * @package App\Models
 */
class Product extends Model
{
    protected $primaryKey = 'no_product';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int',
        'no_product' => 'int',
        'no_partner' => 'int',
        'no_partner_category' => 'int',
        'at_price_before' => 'float',
        'at_price' => 'float',
        'at_price_us' => 'float',
        'at_commission' => 'float',
        'no_sel_group1' => 'int',
        'no_sel_group2' => 'int',
        'no_sel_group3' => 'int',
        'no_sel_group4' => 'int',
        'no_sel_group5' => 'int',
        'at_view_order' => 'int'
    ];

    protected $dates = [
        'dt_upt',
        'dt_del',
        'dt_reg'
    ];

    protected $fillable = [
        'no',
        'no_partner',
        'ds_option_sel',
        'nm_product',
        'ds_content',
        'no_partner_category',
        'at_price_before',
        'at_price',
        'at_price_us',
        'at_commission',
        'ds_image_path',
        'ds_recommend_start_time',
        'ds_recommend_end_time',
        'no_sel_group1',
        'no_sel_group2',
        'no_sel_group3',
        'no_sel_group4',
        'no_sel_group5',
        'yn_new',
        'yn_vote',
        'ds_status',
        'id_upt',
        'dt_upt',
        'id_del',
        'dt_del',
        'id_reg',
        'dt_reg',
        'at_view_order',
        'cd_gas_kind',
        'cd_car_kind'
    ];

    public function partnerCategory(): BelongsTo
    {
        return $this->belongsTo(PartnerCategory::class, 'no_partner_category', 'no_partner_category');
    }

    public function productOptionGroups(): HasMany
    {
        return $this->hasMany(ProductOptionGroup::class, 'no_partner', 'no_partner');
    }

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class, 'no_partner', 'no_partner');
    }
}
