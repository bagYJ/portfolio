<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopHolidayHistory
 *
 * @property int $no
 * @property int $no_holiday
 * @property int $no_shop
 * @property string $cd_holiday
 * @property int|null $nt_weekday
 * @property Carbon|null $dt_imsi_start
 * @property Carbon|null $dt_imsi_end
 * @property Carbon|null $dt_reg
 * @property string|null $id_admin
 *
 * @package App\Models
 */
class ShopHolidayHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_holiday' => 'int',
        'no_shop' => 'int',
        'nt_weekday' => 'int'
    ];

    protected $dates = [
        'dt_imsi_start',
        'dt_imsi_end',
        'dt_reg'
    ];

    protected $fillable = [
        'no_holiday',
        'no_shop',
        'cd_holiday',
        'nt_weekday',
        'dt_imsi_start',
        'dt_imsi_end',
        'dt_reg',
        'id_admin'
    ];
}
