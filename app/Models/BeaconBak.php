<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * Class BeaconBak
 *
 * @property int $no
 * @property int|null $seq
 * @property int|null $no_user
 * @property string|null $ds_car_number
 * @property string|null $cd_gas_kind
 * @property string|null $id_beacon
 * @property int $no_device
 * @property string|null $nm_device
 * @property string|null $ds_sn
 * @property string|null $ds_qrcode
 * @property string|null $ds_macaddr
 * @property string|null $ds_opt
 * @property string|null $ds_userkey
 * @property string|null $ds_adver
 * @property string|null $ds_adver_hex
 * @property string|null $ds_secret
 * @property string|null $ds_encrypt
 * @property string|null $ds_version
 * @property string|null $cd_device_status
 * @property Carbon|null $dt_pairing
 * @property float|null $at_rssi_left
 * @property float|null $at_rssi_top
 * @property float|null $at_rssi_right
 * @property float|null $at_rssi_bottom
 * @property Carbon|null $dt_del
 * @property Carbon|null $dt_reg
 * @property string|null $ds_upt_key
 *
 * @package App\Models
 */
class BeaconBak extends Model
{
    public $incrementing = false;
    public $timestamps = true;

    const CREATED_AT = 'dt_reg';
    const UPDATED_AT = null;
    const DELETED_AT = 'dt_del';

    protected $casts = [
        'no' => 'int',
        'seq' => 'int',
        'no_user' => 'int',
        'no_device' => 'int',
        'at_rssi_left' => 'float',
        'at_rssi_top' => 'float',
        'at_rssi_right' => 'float',
        'at_rssi_bottom' => 'float'
    ];

    protected $dates = [
        'dt_pairing',
        'dt_del',
        'dt_reg'
    ];

    protected $hidden = [
        'ds_secret'
    ];

    protected $fillable = [
        'no',
        'seq',
        'no_user',
        'ds_car_number',
        'cd_gas_kind',
        'id_beacon',
        'no_device',
        'nm_device',
        'ds_sn',
        'ds_qrcode',
        'ds_macaddr',
        'ds_opt',
        'ds_userkey',
        'ds_adver',
        'ds_adver_hex',
        'ds_secret',
        'ds_encrypt',
        'ds_version',
        'cd_device_status',
        'dt_pairing',
        'at_rssi_left',
        'at_rssi_top',
        'at_rssi_right',
        'at_rssi_bottom',
        'dt_del',
        'dt_reg',
        'ds_upt_key'
    ];
}
