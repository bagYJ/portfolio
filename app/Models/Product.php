<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
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
 * @property string|null $yn_car_pickup
 * @property string|null $yn_shop_pickup
 * @property string|null $yn_check_stock
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
 * @property string|null $cd_spc
 *
 * @package App\Models
 */
class Product extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'no_product';
    protected $casts = [];

    protected $fillable = [];

    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class, 'no_partner', 'no_partner');
    }

}
