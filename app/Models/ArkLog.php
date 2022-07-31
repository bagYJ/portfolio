<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ArkLog
 *
 * @property int $no
 * @property string|null $ds_sn
 * @property int|null $no_shop
 * @property string|null $no_shop_ark
 * @property string|null $ds_macaddr
 * @property string|null $cd_ark_status
 * @property string|null $yn_control
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $id_upt
 * @property string|null $ds_nation
 * @property int|null $no_install
 * @property string|null $is_battery_low
 * @property string|null $yn_control_ark
 *
 * @package App\Models
 */
class ArkLog extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'no_install' => 'int'
    ];

    protected $dates = [
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'ds_sn',
        'no_shop',
        'no_shop_ark',
        'ds_macaddr',
        'cd_ark_status',
        'yn_control',
        'dt_reg',
        'dt_upt',
        'id_upt',
        'ds_nation',
        'no_install',
        'is_battery_low',
        'yn_control_ark'
    ];
}
