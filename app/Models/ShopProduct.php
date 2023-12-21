<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopProduct
 *
 * @property int $no
 * @property int $no_product
 * @property int|null $no_shop
 * @property string|null $ds_option_sel
 * @property string|null $nm_product
 * @property string|null $ds_content
 * @property int|null $no_shop_category
 * @property float|null $at_price_before
 * @property float|null $at_price
 * @property float|null $at_price_us
 * @property float|null $at_commission
 * @property string|null $ds_image_path
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
 *
 * @package App\Models
 */
class ShopProduct extends Model
{
    public $incrementing = false;
    public $timestamps = true;
    protected $primaryKey = 'no_product';
    protected $casts = [];

    protected $fillable = [];
}
