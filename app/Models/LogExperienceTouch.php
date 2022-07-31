<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class LogExperienceTouch
 *
 * @property int $no
 * @property int|null $no_shop
 * @property string|null $cd_shop_qr
 * @property string|null $ds_phone_info
 * @property string|null $ds_ip
 * @property Carbon|null $dt_reg
 * @property string|null $cd_reg_flow
 * @property string|null $ds_touch_url
 * @property string|null $ds_touch_page
 *
 * @package App\Models
 */
class LogExperienceTouch extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'cd_shop_qr',
        'ds_phone_info',
        'ds_ip',
        'dt_reg',
        'cd_reg_flow',
        'ds_touch_url',
        'ds_touch_page'
    ];
}
