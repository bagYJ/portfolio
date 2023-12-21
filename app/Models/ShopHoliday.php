<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShopHoliday
 * 
 * @property int $no
 * @property int $no_shop
 * @property string $cd_holiday
 * @property int|null $nt_weekday
 * @property Carbon|null $dt_imsi_start
 * @property Carbon|null $dt_imsi_end
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $cd_imsi_reason
 * @property string|null $ds_content
 *
 * @package App\Models
 */
class ShopHoliday extends Model
{
	protected $table = 'shop_holiday';
	protected $primaryKey = 'no';
	public $timestamps = false;

    protected $casts = [
        'no_shop' => 'int',
        'nt_weekday' => 'int',
        'dt_imsi_start' => 'date',
        'dt_imsi_end' => 'date',
        'dt_reg' => 'date',
        'dt_upt' => 'date'
    ];

	protected $fillable = [
		'no_shop',
		'cd_holiday',
		'nt_weekday',
		'dt_imsi_start',
		'dt_imsi_end',
		'dt_reg',
		'dt_upt',
		'cd_imsi_reason',
		'ds_content'
	];
}
