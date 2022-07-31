<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

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
 *
 * @package App\Models
 */
class ShopHoliday extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'nt_weekday' => 'int'
    ];

    protected $dates = [
        'dt_imsi_start',
        'dt_imsi_end',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no_shop',
        'cd_holiday',
        'nt_weekday',
        'dt_imsi_start',
        'dt_imsi_end',
        'dt_reg',
        'dt_upt',
        'cd_imsi_reason'
    ];
}
