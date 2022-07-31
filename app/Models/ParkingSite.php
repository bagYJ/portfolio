<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ParkingSite
 * 
 * @property int $no_site
 * @property string $type
 * @property string $id_site
 * @property int|null $no_parking_site
 * @property string|null $id_auto_parking
 * @property string $nm_shop
 * @property string|null $ds_category
 * @property string|null $ds_option_tag
 * @property float $at_price
 * @property string $ds_price_info
 * @property string|null $ds_time_info
 * @property float|null $at_basic_price
 * @property int|null $at_basic_time
 * @property string|null $ds_tel
 * @property string|null $ds_info
 * @property float $at_lat
 * @property float $at_lng
 * @property string $ds_address
 * @property string $ds_operation_time
 * @property string|null $ds_caution
 * @property string|null $auto_biz_type
 * @property string|null $auto_biz_time
 * @property string|null $auto_sat_biz_type
 * @property string|null $auto_sat_biz_time
 * @property string|null $auto_hol_biz_type
 * @property string|null $auto_hol_biz_time
 * @property float $at_pg_commission_rate
 * @property string|null $cd_commission_type
 * @property float $at_commission_amount
 * @property float $at_commission_rate
 * @property float $at_sales_commission_rate
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * 
 * @property Collection|ParkingSiteImage[] $parking_site_images
 * @property Collection|ParkingSiteTicket[] $parking_site_tickets
 *
 * @package App\Models
 */
class ParkingSite extends Model
{
	protected $table = 'parking_site';
	protected $primaryKey = 'no_site';
	public $timestamps = false;

	protected $casts = [
		'no_parking_site' => 'int',
		'at_price' => 'float',
		'at_basic_price' => 'float',
		'at_basic_time' => 'int',
		'at_lat' => 'float',
		'at_lng' => 'float',
		'at_pg_commission_rate' => 'float',
		'at_commission_amount' => 'float',
		'at_commission_rate' => 'float',
		'at_sales_commission_rate' => 'float'
	];

	protected $dates = [
		'dt_reg',
		'dt_upt'
	];

	protected $fillable = [
        'id_site',
		'no_parking_site',
		'id_auto_parking',
		'nm_shop',
		'ds_category',
		'ds_option_tag',
		'at_price',
		'ds_price_info',
		'ds_time_info',
		'at_basic_price',
		'at_basic_time',
		'ds_tel',
		'ds_info',
		'at_lat',
		'at_lng',
		'ds_address',
		'ds_operation_time',
		'ds_caution',
		'auto_biz_type',
		'auto_biz_time',
		'auto_sat_biz_type',
		'auto_sat_biz_time',
		'auto_hol_biz_type',
		'auto_hol_biz_time',
		'at_pg_commission_rate',
		'cd_commission_type',
		'at_commission_amount',
		'at_commission_rate',
		'at_sales_commission_rate',
		'dt_reg',
		'dt_upt'
	];

    public function parkingSiteImages(): HasMany
    {
        return $this->hasMany(ParkingSiteImage::class, 'no_parking_site', 'no_parking_site');
    }

    public function parkingSiteTickets(): HasMany
    {
        return $this->hasMany(ParkingSiteTicket::class, 'no_parking_site', 'no_parking_site');
    }
}
