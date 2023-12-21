<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class RetailProductOption
 * 
 * @property int $no
 * @property int $no_option
 * @property int $no_partner
 * @property int $no_group
 * @property string|null $no_barcode_opt
 * @property int|null $no_product_opt
 * @property string|null $nm_product_opt
 * @property float|null $at_price_opt
 * @property string|null $ds_status
 * @property int|null $at_view
 * @property string|null $id_upt
 * @property Carbon|null $dt_upt
 * @property string|null $id_del
 * @property Carbon|null $dt_del
 * @property string|null $id_reg
 * @property Carbon|null $dt_reg
 * @property string|null $yn_del
 *
 * @package App\Models
 */
class RetailProductOption extends Model
{
	protected $primaryKey = 'no';
	public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = 'dt_del';

	protected $casts = [];

	protected $fillable = [];
}
