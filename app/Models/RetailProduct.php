<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class RetailProduct
 * 
 * @property int $no
 * @property int $no_product
 * @property int $no_partner
 * @property int|null $no_category
 * @property int|null $no_sub_category
 * @property string|null $nm_product
 * @property string|null $ds_content
 * @property float|null $at_price_before
 * @property float|null $at_price
 * @property Carbon|null $dt_sale_st
 * @property Carbon|null $dt_sale_end
 * @property string|null $ds_image_path
 * @property string|null $ds_detail_image_path
 * @property string|null $no_barcode
 * @property string|null $cd_discount_sale
 * @property string|null $yn_option
 * @property string|null $yn_new
 * @property string|null $yn_vote
 * @property string|null $yn_show
 * @property int|null $at_view
 * @property string|null $ds_status
 * @property string|null $ds_avn_status
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class RetailProduct extends Model
{
	protected $primaryKey = 'no';
	public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = 'dt_del';

	protected $casts = [];

	protected $fillable = [];
}
