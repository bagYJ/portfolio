<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkingCouponEvent
 * 
 * @property int $no
 * @property string $nm_event
 * @property array|null $no_sites
 * @property string $cd_disct_type
 * @property int $at_disct_money
 * @property float $at_disct_rate
 * @property Carbon|null $dt_start
 * @property Carbon|null $dt_end
 * @property int|null $at_expire_day
 * @property Carbon $dt_reg
 * @property Carbon|null $dt_upt
 * @property Carbon|null $dt_del
 *
 * @package App\Models
 */
class ParkingCouponEvent extends Model
{
	protected $table = 'parking_coupon_event';
	protected $primaryKey = 'no';
	public $timestamps = true;

    public const CREATED_AT = 'dt_reg';
    public const UPDATED_AT = 'dt_upt';
    public const DELETED_AT = 'dt_del';

	protected $casts = [
		'no_sites' => 'json',
		'at_disct_money' => 'int',
		'at_disct_rate' => 'float',
		'at_expire_day' => 'int'
	];

	protected $dates = [
		'dt_start',
		'dt_end',
		'dt_reg',
		'dt_upt',
		'dt_del'
	];

	protected $fillable = [
		'nm_event',
		'no_sites',
		'cd_cpe_status',
		'cd_disct_type',
		'at_disct_money',
		'at_disct_rate',
		'dt_start',
		'dt_end',
		'at_expire_day',
		'dt_reg',
		'dt_upt',
		'dt_del'
	];
}
