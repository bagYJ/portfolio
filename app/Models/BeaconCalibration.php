<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BeaconCalibration
 *
 * @property int $no
 * @property int|null $no_user
 * @property int|null $no_device
 * @property string|null $ds_rssi_left
 * @property string|null $ds_rssi_top
 * @property string|null $ds_rssi_right
 * @property string|null $ds_rssi_bottom
 * @property Carbon|null $dt_reg
 *
 * @package App\Models
 */
class BeaconCalibration extends Model
{
    protected $primaryKey = 'no';
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = null;

    protected $casts = [
        'no_user' => 'int',
        'no_device' => 'int'
    ];

    protected $dates = [
        'dt_reg'
    ];

    protected $fillable = [
        'no_user',
        'no_device',
        'ds_rssi_left',
        'ds_rssi_top',
        'ds_rssi_right',
        'ds_rssi_bottom',
        'dt_reg'
    ];
}
