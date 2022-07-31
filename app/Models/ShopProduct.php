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
    protected $primaryKey = 'no_product';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no' => 'int',
        'no_product' => 'int',
        'no_shop' => 'int',
        'no_shop_category' => 'int',
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
        'no_shop',
        'ds_option_sel',
        'nm_product',
        'ds_content',
        'no_shop_category',
        'at_price_before',
        'at_price',
        'at_price_us',
        'at_commission',
        'ds_image_path',
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
}
