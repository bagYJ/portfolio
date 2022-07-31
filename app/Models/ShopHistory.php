<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class ShopHistory
 *
 * @property int $no
 * @property int $no_shop
 * @property string|null $ds_status
 * @property string|null $yn_operation
 * @property string|null $cd_status_open
 * @property int|null $at_alarm_rssi
 * @property Carbon|null $dt_reg
 * @property string $id_admin
 * @property string|null $cd_admin_access_page
 *
 * @package App\Models
 */
class ShopHistory extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_shop' => 'int',
        'at_alarm_rssi' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_shop',
        'ds_status',
        'yn_operation',
        'cd_status_open',
        'at_alarm_rssi',
        'dt_reg',
        'id_admin',
        'cd_admin_access_page'
    ];
}
