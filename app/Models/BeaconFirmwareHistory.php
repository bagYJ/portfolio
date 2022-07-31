<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BeaconFirmwareHistory
 *
 * @property int $no
 * @property int|null $no_device
 * @property string|null $ds_sn
 * @property string|null $ds_firmware
 * @property Carbon|null $dt_upt_firmware
 *
 * @package App\Models
 */
class BeaconFirmwareHistory extends Model
{
    protected $primaryKey = 'no';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'no' => 'int',
        'no_device' => 'int'
    ];

    protected $dates = [
        'dt_upt_firmware'
    ];

    protected $fillable = [
        'no_device',
        'ds_sn',
        'ds_firmware',
        'dt_upt_firmware'
    ];
}
