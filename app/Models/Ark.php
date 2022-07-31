<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class Ark
 *
 * @property int $no
 * @property string $ds_sn
 * @property int $no_shop
 * @property string $no_shop_ark
 * @property string|null $ds_macaddr
 * @property string|null $cd_ark_status
 * @property string|null $yn_control
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $ds_nation
 * @property int|null $no_install
 * @property string|null $is_battery_low
 * @property string|null $yn_control_ark
 * @property string|null $ds_version
 *
 * @package App\Models
 */
class Ark extends Model
{
    protected $primaryKey = 'ds_sn';
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no' => 'int',
        'no_shop' => 'int',
        'no_install' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'no',
        'no_shop',
        'no_shop_ark',
        'ds_macaddr',
        'cd_ark_status',
        'yn_control',
        'dt_reg',
        'dt_upt',
        'ds_nation',
        'no_install',
        'is_battery_low',
        'yn_control_ark',
        'ds_version'
    ];
}
