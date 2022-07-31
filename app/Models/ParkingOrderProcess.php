<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ParkingOrderProcess
 * 
 * @property int $no
 * @property int $no_order
 * @property int $no_user
 * @property string $id_site
 * @property int|null $no_parking_site
 * @property string|null $id_auto_parking
 * @property string $cd_order_process
 * @property Carbon $dt_order_process
 *
 * @package App\Models
 */
class ParkingOrderProcess extends Model
{
	protected $table = 'parking_order_process';
	protected $primaryKey = 'no';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'no' => 'int',
		'no_order' => 'int',
		'no_user' => 'int',
		'no_parking_site' => 'int'
	];

	protected $dates = [
		'dt_order_process'
	];

	protected $fillable = [
		'no_order',
		'no_user',
		'no_parking_site',
		'id_auto_parking',
		'cd_order_process',
		'dt_order_process'
	];
}
