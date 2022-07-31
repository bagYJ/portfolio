<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ArkControlLog
 *
 * @property int $no
 * @property string $ds_sn
 * @property int $no_shop
 * @property string|null $no_shop_ark
 * @property string|null $cd_ark_status
 * @property string|null $yn_control
 * @property string|null $ds_server_ip
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class ArkControlLog extends Model
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
        'ds_sn',
        'no_shop',
        'no_shop_ark',
        'cd_ark_status',
        'yn_control',
        'ds_server_ip',
        'dt_reg'
    ];
}
