<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class DeviceInstall
 *
 * @property Carbon $dt_install_date
 * @property Carbon $dt_install_time
 * @property string|null $cd_device
 * @property int|null $at_count
 * @property string|null $cd_install_type
 * @property string|null $yn_install
 * @property string|null $ds_address
 * @property string|null $ds_address2
 * @property Carbon|null $dt_reg
 * @property Carbon|null $dt_upt
 * @property string|null $id_admin
 * @property int $no_install
 * @property int $no_install_target
 *
 * @package App\Models
 */
class DeviceInstall extends Model
{
    protected $primaryKey = 'no_install';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = 'dt_upt';
    const DELETED_AT = null;

    protected $casts = [
        'at_count' => 'int',
        'no_install_target' => 'int'
    ];

    protected $dates = [
        'dt_install_date',
        'dt_install_time',
        'dt_reg',
        'dt_upt'
    ];

    protected $fillable = [
        'dt_install_date',
        'dt_install_time',
        'cd_device',
        'at_count',
        'cd_install_type',
        'yn_install',
        'ds_address',
        'ds_address2',
        'dt_reg',
        'dt_upt',
        'id_admin',
        'no_install_target'
    ];
}
