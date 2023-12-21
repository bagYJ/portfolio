<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopOptTime
 * 
 * @property int $no_shop
 * @property int $nt_weekday
 * @property string|null $ds_open_time
 * @property string|null $ds_close_time
 * @property string|null $ds_open_order_time
 * @property string|null $ds_close_order_time
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $cd_break_time
 * @property string|null $ds_break_start_time
 * @property string|null $ds_break_end_time
 * @property string|null $cd_break_time2
 * @property string|null $ds_break_start_time2
 * @property string|null $ds_break_end_time2
 *
 * @package App\Models
 */
class ShopOptTime extends Model
{
	protected $table = 'shop_opt_time';
	public $incrementing = false;
	public $timestamps = false;

    protected $casts = [
        'no_shop' => 'int',
        'nt_weekday' => 'int',
        'dt_reg' => 'date',
        'dt_upt' => 'date'
    ];

	protected $fillable = [
		'ds_open_time',
		'ds_close_time',
		'ds_open_order_time',
		'ds_close_order_time',
		'dt_reg',
		'dt_upt',
		'cd_break_time',
		'ds_break_start_time',
		'ds_break_end_time',
		'cd_break_time2',
		'ds_break_start_time2',
		'ds_break_end_time2'
	];
}
